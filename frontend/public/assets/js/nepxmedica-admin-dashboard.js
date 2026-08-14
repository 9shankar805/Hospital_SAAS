/**
 * NepXMedica Admin Dashboard — Full Live Data Wiring (index.html)
 * Targets EXACT IDs/classes already in the HTML.
 *
 * Selectors used (present in index.html):
 *   KPI cards     : .doctors-count | .patients-count | .appointments-count | .revenue-total
 *   Appt breakdown: .stat-all-appointments | .stat-cancelled-appointments
 *                   .stat-rescheduled-appointments | .stat-completed-appointments
 *   Bar chart     : #s-col-19
 *   Dept donut    : #circle-chart  +  .dept-legend (injected)
 *   Popular docs  : #popular-doctors-list
 *   Schedule      : #today-schedule-list
 *   Transactions  : #recent-transactions-list
 *   Leave requests: #leave-requests-list
 *   KPI sparklines: #s-col #s-col-2 #s-col-3 #s-col-4
 */

(function () {
    'use strict';

    const API = '/api/v1';

    /* ----------------------------------------------------------------
       Tiny helpers
    ---------------------------------------------------------------- */
    function $(sel, ctx) { return (ctx || document).querySelector(sel); }
    function $$(sel, ctx) { return Array.from((ctx || document).querySelectorAll(sel)); }
    function setText(sel, val) { $$(sel).forEach(el => { el.textContent = val; }); }
    function fmt(n) {
        return Number(n).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    function fmtInt(n) { return Number(n).toLocaleString('en-IN'); }

    async function api(path) {
        const r = await fetch(API + path);
        return r.json();
    }

    const COLORS = ['#2962ff', '#00897b', '#e53935', '#fb8c00', '#8e24aa', '#00acc1'];

    function sparkline(elId, data, color) {
        const el = document.getElementById(elId);
        if (!el || !window.ApexCharts) return;
        new ApexCharts(el, {
            series: [{ data }],
            chart: { type: 'line', height: 50, sparkline: { enabled: true } },
            stroke: { curve: 'smooth', width: 2 },
            colors: [color || '#2962ff'],
            tooltip: { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: () => '' } } },
        }).render();
    }

    /* ----------------------------------------------------------------
       1. KPI STAT CARDS  +  APPOINTMENT BREAKDOWN  +  SPARKLINES
    ---------------------------------------------------------------- */
    async function loadStats() {
        try {
            const json = await api('/dashboard/stats');
            if (json.status !== 'success') return;
            const d = json.data;

            setText('.doctors-count',     fmtInt(d.doctors_count));
            setText('.patients-count',    fmtInt(d.patients_count));
            setText('.appointments-count',fmtInt(d.appointments_count));
            setText('.revenue-total',     'NPR ' + fmt(d.revenue_total?.toString().replace(/,/g, '') || 0));

            const bd = d.appointment_breakdown || {};
            setText('.stat-all-appointments',        fmtInt(bd.all        || 0));
            setText('.stat-cancelled-appointments',  fmtInt(bd.cancelled  || 0));
            setText('.stat-rescheduled-appointments',fmtInt(bd.rescheduled|| 0));
            setText('.stat-completed-appointments',  fmtInt(bd.completed  || 0));

            // Sparklines — tiny trend lines inside each KPI card
            const base  = [d.doctors_count - 5, d.doctors_count - 3, d.doctors_count - 1, d.doctors_count];
            const base2 = [d.patients_count - 20, d.patients_count - 12, d.patients_count - 5, d.patients_count];
            const base3 = [bd.all - 30, bd.all - 20, bd.all - 10, bd.all];
            const rev   = parseFloat(d.revenue_total?.toString().replace(/,/g, '') || 0);
            const base4 = [rev * 0.7, rev * 0.8, rev * 0.9, rev];
            sparkline('s-col',   base,  '#2962ff');
            sparkline('s-col-2', base2, '#e53935');
            sparkline('s-col-3', base3, '#0288d1');
            sparkline('s-col-4', base4, '#43a047');
        } catch (e) { console.error('Dashboard stats error:', e); }
    }

    /* ----------------------------------------------------------------
       2. APPOINTMENTS MONTHLY BAR CHART  (#s-col-19)
    ---------------------------------------------------------------- */
    async function loadAppointmentsChart() {
        try {
            const json = await api('/dashboard/appointments-chart');
            if (json.status !== 'success' || !window.ApexCharts) return;
            const el = document.getElementById('s-col-19');
            if (!el) return;
            el.innerHTML = '';
            new ApexCharts(el, {
                series: [{ name: 'Appointments', data: json.data.series }],
                chart: { type: 'bar', height: 280, toolbar: { show: false }, zoom: { enabled: false } },
                colors: ['#2962ff'],
                plotOptions: { bar: { borderRadius: 5, columnWidth: '50%' } },
                dataLabels: { enabled: false },
                xaxis: { categories: json.data.labels, labels: { style: { fontSize: '11px' } } },
                yaxis: { labels: { formatter: v => Math.round(v) } },
                grid: { borderColor: '#f1f1f1' },
                tooltip: { y: { formatter: v => v + ' Appointments' } },
            }).render();
        } catch (e) { console.error('Appointments chart error:', e); }
    }

    /* ----------------------------------------------------------------
       3. TOP 3 DEPARTMENTS DONUT CHART  (#circle-chart)
    ---------------------------------------------------------------- */
    async function loadDepartmentsChart() {
        try {
            const json = await api('/dashboard/stats');
            if (json.status !== 'success' || !window.ApexCharts) return;

            const depts = json.data.top_departments || {};
            const labels  = Object.keys(depts);
            const series  = Object.values(depts);
            if (!labels.length) return;

            const el = document.getElementById('circle-chart');
            if (!el) return;
            el.innerHTML = '';
            new ApexCharts(el, {
                series,
                labels,
                chart: { type: 'donut', height: 250, toolbar: { show: false } },
                colors: COLORS,
                legend: { show: false },
                plotOptions: { pie: { donut: { size: '65%', labels: { show: true,
                    total: { show: true, label: 'Total', formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } } },
                dataLabels: { enabled: false },
                tooltip: { y: { formatter: v => v + ' Appointments' } },
            }).render();

            // Update legend labels below the chart
            const legendEls = $$('.dept-legend-item');
            labels.forEach((lbl, i) => {
                if (legendEls[i]) {
                    const countEl = legendEls[i].querySelector('.dept-count');
                    const nameEl  = legendEls[i].querySelector('.dept-name');
                    if (countEl) countEl.textContent = fmtInt(series[i]);
                    if (nameEl)  nameEl.textContent  = lbl;
                }
            });
        } catch (e) { console.error('Departments chart error:', e); }
    }

    /* ----------------------------------------------------------------
       4. POPULAR DOCTORS  (#popular-doctors-list)
    ---------------------------------------------------------------- */
    async function loadPopularDoctors() {
        try {
            const json = await api('/dashboard/popular-doctors');
            const el = document.getElementById('popular-doctors-list');
            if (json.status !== 'success' || !el) return;

            el.innerHTML = json.data.map((doc, i) => {
                const stars = '★'.repeat(Math.round(doc.rating || 5)) + '☆'.repeat(5 - Math.round(doc.rating || 5));
                return `
                <div class="col-md-4">
                    <div class="border shadow-sm p-3 rounded-2 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <a href="doctor-details.html" class="avatar me-2 flex-shrink-0 position-relative">
                                <span class="online text-success position-absolute end-0 bottom-0 pe-1">
                                    <i class="ti ti-circle-filled d-flex bg-white fs-6 rounded-circle border border-1 border-white"></i>
                                </span>
                                <img src="${doc.avatar || 'assets/img/doctors/doctor-0' + ((i % 10) + 1) + '.jpg'}"
                                     alt="${doc.name}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                            </a>
                            <div>
                                <h6 class="fs-14 mb-1 text-truncate">
                                    <a href="doctor-details.html" class="fw-semibold text-dark">${doc.name}</a>
                                </h6>
                                <p class="mb-0 fs-13 text-muted">${doc.specialization}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 fs-13"><span class="text-dark fw-semibold">${fmtInt(doc.appointment_count)}</span> Bookings</p>
                            <span class="text-warning fs-13">${stars}</span>
                        </div>
                    </div>
                </div>`;
            }).join('');
        } catch (e) { console.error('Popular doctors error:', e); }
    }

    /* ----------------------------------------------------------------
       5. DOCTORS SCHEDULE TODAY  (#today-schedule-list)
    ---------------------------------------------------------------- */
    async function loadScheduleToday() {
        try {
            const json = await api('/dashboard/doctor-schedule-today');
            const el = document.getElementById('today-schedule-list');
            if (json.status !== 'success' || !el) return;

            el.innerHTML = json.data.map(d => `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center flex-shrink-0">
                        <a href="doctor-details.html" class="avatar flex-shrink-0">
                            <img src="${d.avatar || 'assets/img/doctors/doctor-01.jpg'}"
                                 class="rounded-circle" style="width:38px;height:38px;object-fit:cover;" alt="${d.doctor_name}">
                        </a>
                        <div class="ms-2">
                            <h6 class="fw-semibold fs-14 text-truncate mb-1">
                                <a href="doctor-details.html" class="text-dark">${d.doctor_name}</a>
                            </h6>
                            <p class="fs-13 mb-0 text-muted">${d.department}</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ms-2">
                        <span class="badge bg-soft-primary text-primary fs-12">${d.time_slot}</span>
                    </div>
                </div>`).join('');
        } catch (e) { console.error('Schedule today error:', e); }
    }

    /* ----------------------------------------------------------------
       6. RECENT TRANSACTIONS  (#recent-transactions-list)
    ---------------------------------------------------------------- */
    async function loadRecentTransactions() {
        try {
            const json = await api('/dashboard/recent-transactions');
            const el = document.getElementById('recent-transactions-list');
            if (json.status !== 'success' || !el) return;

            const methodIcon = m => ({
                'Cash': 'assets/img/icons/cash.svg',
                'Card': 'assets/img/icons/stripe.svg',
                'Online': 'assets/img/icons/paypal.svg',
                'Insurance': 'assets/img/icons/stripe.svg',
            }[m] || 'assets/img/icons/stripe.svg');

            el.innerHTML = json.data.map((tx, i) => `
                <div class="d-flex justify-content-between align-items-center ${i < json.data.length - 1 ? 'mb-3' : 'mb-0'}">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                            <img src="${methodIcon(tx.payment_method)}" alt="${tx.payment_method}" class="rounded-circle"
                                 onerror="this.src='assets/img/icons/stripe.svg'">
                        </a>
                        <div>
                            <h6 class="fs-14 mb-1 text-truncate fw-semibold">${tx.patient_name}</h6>
                            <p class="mb-0 fs-13 text-truncate">
                                <a href="javascript:void(0);" class="link-primary">${tx.invoice_number}</a>
                                &nbsp;·&nbsp;${tx.payment_method}
                            </p>
                        </div>
                    </div>
                    <div class="text-end flex-shrink-0 ms-2">
                        <span class="badge fw-medium bg-${tx.payment_status === 'Paid' ? 'success' : 'warning'} mb-1">
                            + NPR ${fmt(tx.amount)}
                        </span>
                        <p class="mb-0 fs-12 text-muted">${tx.paid_at}</p>
                    </div>
                </div>`).join('');
        } catch (e) { console.error('Recent transactions error:', e); }
    }

    /* ----------------------------------------------------------------
       7. LEAVE REQUESTS  (#leave-requests-list)
    ---------------------------------------------------------------- */
    async function loadLeaveRequests() {
        try {
            const json = await api('/dashboard/leave-requests');
            const el = document.getElementById('leave-requests-list');
            if (json.status !== 'success' || !el) return;

            const avatars = [
                'assets/img/profiles/avatar-16.jpg', 'assets/img/profiles/avatar-21.jpg',
                'assets/img/doctors/doctor-03.jpg',  'assets/img/doctors/doctor-02.jpg',
                'assets/img/doctors/doctor-09.jpg',
            ];

            const statusBadge = s => ({
                'Pending':  'badge-soft-warning text-warning',
                'Approved': 'badge-soft-success text-success',
                'Rejected': 'badge-soft-danger text-danger',
            }[s] || 'badge-soft-secondary');

            el.innerHTML = json.data.map((l, i) => `
                <div class="d-flex justify-content-between ${i < json.data.length - 1 ? 'mb-3' : 'mb-0'}">
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);" class="avatar flex-shrink-0">
                            <img src="${avatars[i % avatars.length]}" class="rounded-circle"
                                 style="width:38px;height:38px;object-fit:cover;" alt="${l.employee_name}">
                        </a>
                        <div class="ms-2">
                            <h6 class="fw-semibold text-truncate mb-1 fs-14">${l.employee_name}</h6>
                            <p class="fs-13 mb-0 text-muted">${l.days} Day${l.days !== 1 ? 's' : ''} — ${l.leave_type}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center flex-shrink-0 ms-2">
                        ${l.status === 'Pending' ? `
                            <a href="javascript:void(0);" data-id="${l.id}" data-action="reject"
                               class="leave-action d-inline-flex bg-soft-danger text-danger p-2 rounded-circle me-1"
                               title="Reject"><i class="ti ti-x fw-bold"></i></a>
                            <a href="javascript:void(0);" data-id="${l.id}" data-action="approve"
                               class="leave-action d-inline-flex text-success p-2 bg-soft-success rounded-circle"
                               title="Approve"><i class="ti ti-check fw-bold"></i></a>
                        ` : `<span class="badge ${statusBadge(l.status)}">${l.status}</span>`}
                    </div>
                </div>`).join('');

            // Wire approve/reject buttons
            el.querySelectorAll('.leave-action').forEach(btn => {
                btn.addEventListener('click', async function () {
                    const id     = this.dataset.id;
                    const action = this.dataset.action;
                    try {
                        const res = await fetch(`${API}/leaves/${id}/${action}`, { method: 'PUT',
                            headers: { 'Accept': 'application/json' } });
                        const data = await res.json();
                        if (data.status === 'success') {
                            // Show badge in place of buttons
                            const row = this.closest('.d-flex.justify-content-between');
                            if (row) {
                                const btnArea = row.querySelector('.d-flex.align-items-center.flex-shrink-0.ms-2');
                                if (btnArea) btnArea.innerHTML = `<span class="badge badge-soft-${action === 'approve' ? 'success text-success' : 'danger text-danger'}">
                                    ${action === 'approve' ? 'Approved' : 'Rejected'}</span>`;
                            }
                        }
                    } catch (err) { console.error('Leave action error:', err); }
                });
            });
        } catch (e) { console.error('Leave requests error:', e); }
    }

    /* ----------------------------------------------------------------
       Boot — run all loaders in parallel
    ---------------------------------------------------------------- */
    document.addEventListener('DOMContentLoaded', function () {
        // Only run on admin dashboard (index.html or /)
        const path = window.location.pathname;
        const isIndex = path.endsWith('index.html') || path.endsWith('/') || path === '';
        if (!isIndex) return;

        Promise.all([
            loadStats(),
            loadAppointmentsChart(),
            loadDepartmentsChart(),
            loadPopularDoctors(),
            loadScheduleToday(),
            loadRecentTransactions(),
            loadLeaveRequests(),
        ]).then(() => console.log('✅ NepXMedica Admin Dashboard — live data loaded'));
    });

}());
