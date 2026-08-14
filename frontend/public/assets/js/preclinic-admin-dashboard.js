/**
 * NepXMedica Admin Dashboard Complete Real-Data Integrator (index.html)
 * Dynamically binds MySQL database APIs to all cards, tables, widgets, and lists on the dashboard.
 */

document.addEventListener('DOMContentLoaded', async () => {
    const API_BASE = '/api/v1';

    // Helper: Safe innerText updater
    function setText(selector, text) {
        document.querySelectorAll(selector).forEach(el => { el.innerText = text; });
    }

    // 1. TOP KPI STAT CARDS & APPOINTMENT BREAKDOWN
    try {
        const res = await fetch(`${API_BASE}/dashboard/stats`);
        const json = await res.json();

        if (json.status === 'success') {
            const d = json.data;

            // KPI Cards
            setText('.doctors-count, [data-stat="doctors"]', d.doctors_count);
            setText('.patients-count, [data-stat="patients"]', d.patients_count);
            setText('.appointments-count, [data-stat="appointments"]', d.appointments_count);
            setText('.revenue-total, [data-stat="revenue"]', `NPR ${d.revenue_total}`);

            // Appointment Breakdown
            if (d.appointment_breakdown) {
                setText('.stat-all-appointments', d.appointment_breakdown.all);
                setText('.stat-completed-appointments', d.appointment_breakdown.completed);
                setText('.stat-rescheduled-appointments', d.appointment_breakdown.rescheduled);
                setText('.stat-cancelled-appointments', d.appointment_breakdown.cancelled);
            }
        }
    } catch (err) {
        console.error('Error loading dashboard stats:', err);
    }

    // 2. APPOINTMENTS MONTHLY BAR CHART
    try {
        const chartRes = await fetch(`${API_BASE}/dashboard/appointments-chart`);
        const chartJson = await chartRes.json();

        if (chartJson.status === 'success' && window.ApexCharts) {
            const container = document.querySelector('#appointment-chart, #appointments_chart, #s-col-19');
            if (container) {
                const options = {
                    series: [{ name: 'Appointments', data: chartJson.data.series }],
                    chart: { type: 'bar', height: 320, toolbar: { show: false } },
                    colors: ['#007bff'],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: chartJson.data.labels },
                    yaxis: { labels: { formatter: (val) => Math.round(val) } },
                    grid: { borderColor: '#f1f1f1' }
                };
                container.innerHTML = '';
                new ApexCharts(container, options).render();
            }
        }
    } catch (err) {
        console.error('Error rendering appointments chart:', err);
    }

    // 3. POPULAR DOCTORS
    try {
        const docRes = await fetch(`${API_BASE}/dashboard/popular-doctors`);
        const docJson = await docRes.json();
        const container = document.querySelector('#popular-doctors-list, .popular-doctors-container');

        if (docJson.status === 'success' && container && docJson.data) {
            container.innerHTML = docJson.data.map(doc => `
                <div class="col-md-4 mb-3">
                    <div class="border shadow-sm p-3 rounded-2 bg-white">
                        <div class="d-flex align-items-center mb-3">
                            <a href="doctor-details.html" class="avatar me-2 flex-shrink-0 position-relative">
                                <span class="online text-success position-absolute end-0 bottom-0 pe-1"><i class="ti ti-circle-filled fs-6"></i></span>
                                <img src="${doc.avatar}" alt="img" class="rounded-circle" width="40" height="40">
                            </a>
                            <div>
                                <h6 class="fs-14 mb-1 text-truncate"><a href="doctor-details.html" class="fw-semibold text-dark">${doc.name}</a></h6>
                                <p class="mb-0 fs-13 text-muted">${doc.specialization}</p>
                            </div>
                        </div>
                        <p class="mb-0"><span class="text-dark fw-semibold">${doc.appointment_count}</span> Bookings • <span class="text-warning">★ ${doc.rating}</span></p>
                    </div>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading popular doctors:', err);
    }

    // 4. DOCTORS SCHEDULE TODAY
    try {
        const schedRes = await fetch(`${API_BASE}/dashboard/doctor-schedule-today`);
        const schedJson = await schedRes.json();
        const container = document.querySelector('#today-schedule-list, .doctors-schedule-container');

        if (schedJson.status === 'success' && container && schedJson.data) {
            container.innerHTML = schedJson.data.map(sch => `
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded-2">
                    <div class="d-flex align-items-center">
                        <a href="doctor-details.html" class="avatar flex-shrink-0 me-2">
                            <img src="${sch.avatar}" class="rounded-circle" width="38" height="38" alt="img">
                        </a>
                        <div>
                            <h6 class="fw-semibold fs-14 text-truncate mb-0"><a href="doctor-details.html" class="text-dark">${sch.doctor_name}</a></h6>
                            <p class="fs-12 text-muted mb-0">${sch.department}</p>
                        </div>
                    </div>
                    <span class="badge bg-success-light text-success fs-12">${sch.time_slot}</span>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading doctor schedule:', err);
    }

    // 5. TOP PATIENTS
    try {
        const patRes = await fetch(`${API_BASE}/dashboard/top-patients`);
        const patJson = await patRes.json();
        const container = document.querySelector('#top-patients-list, .top-patients-container');

        if (patJson.status === 'success' && container && patJson.data) {
            container.innerHTML = patJson.data.map(p => `
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <img src="${p.avatar}" class="rounded-circle me-3" width="40" height="40" alt="img">
                        <div>
                            <h6 class="fw-bold mb-0 fs-14">${p.name}</h6>
                            <small class="text-muted">${p.patient_code} • ${p.gender}, ${p.age} yrs</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-light text-primary fw-semibold">${p.visits_count} Visits</span>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading top patients:', err);
    }

    // 6. RECENT TRANSACTIONS TABLE / LIST
    try {
        const txRes = await fetch(`${API_BASE}/dashboard/recent-transactions`);
        const txJson = await txRes.json();
        const container = document.querySelector('#recent-transactions-list, .recent-transactions-container');

        if (txJson.status === 'success' && container && txJson.data) {
            container.innerHTML = txJson.data.map(tx => `
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0 fs-14">${tx.patient_name}</h6>
                        <small class="text-muted">${tx.invoice_number} • ${tx.payment_method}</small>
                    </div>
                    <div class="text-end">
                        <span class="fw-bold text-success">NPR ${tx.amount}</span>
                        <div class="small text-muted">${tx.paid_at}</div>
                    </div>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading recent transactions:', err);
    }

    // 7. LEAVE REQUESTS
    try {
        const leaveRes = await fetch(`${API_BASE}/dashboard/leave-requests`);
        const leaveJson = await leaveRes.json();
        const container = document.querySelector('#leave-requests-list, .leave-requests-container');

        if (leaveJson.status === 'success' && container && leaveJson.data) {
            container.innerHTML = leaveJson.data.map(l => `
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 border-bottom">
                    <div>
                        <h6 class="fw-semibold fs-14 mb-0">${l.employee_name}</h6>
                        <small class="text-muted">${l.leave_type} (${l.days} days)</small>
                    </div>
                    <div>
                        <span class="badge bg-warning-light text-warning me-1">${l.status}</span>
                        <button class="btn btn-xs btn-outline-success p-1" onclick="alert('Leave Approved')"><i class="ti ti-check"></i></button>
                    </div>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error('Error loading leave requests:', err);
    }
});
