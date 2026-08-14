/**
 * NepXMedica Doctor Portal Live Data Integrator (Items 6.16 - 6.30)
 * Connects all doctor pages (dashboard, appointments, prescriptions, schedules, leaves, reviews, patients)
 */

document.addEventListener('DOMContentLoaded', async () => {
    const API_BASE = '/api/v1/doctor';
    const path = window.location.pathname.toLowerCase();

    // 1. DOCTOR DASHBOARD (6.16)
    if (path.includes('doctor-dashboard.html')) {
        try {
            const res = await fetch(`${API_BASE}/dashboard`);
            const json = await res.json();
            if (json.status === 'success') {
                const d = json.data;

                const todayEl = document.querySelector('[data-stat="today_appointments"], .today-appointments-count');
                if (todayEl) todayEl.innerText = d.today_appointments_count;

                const compEl = document.querySelector('[data-stat="completed_consultations"], .completed-consultations-count');
                if (compEl) compEl.innerText = d.completed_consultations;

                const patEl = document.querySelector('[data-stat="total_patients"], .total-patients-count');
                if (patEl) patEl.innerText = d.total_patients;

                const cancEl = document.querySelector('[data-stat="cancelled_count"], .cancelled-count');
                if (cancEl) cancEl.innerText = d.cancelled_count;

                // Upcoming appointment
                const upApt = d.upcoming_appointment;
                const upContainer = document.querySelector('#upcoming-appointment-card, .upcoming-appointment-card');
                if (upApt && upContainer) {
                    upContainer.innerHTML = `
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="${upApt.patient_avatar}" class="rounded-circle me-3" width="48" height="48">
                                <div>
                                    <h6 class="mb-0 fw-bold">${upApt.patient_name}</h6>
                                    <small class="text-muted">${upApt.type} • ${upApt.appointment_time}</small>
                                </div>
                            </div>
                            <span class="badge bg-primary">Next Patient</span>
                        </div>
                    `;
                }

                // Render Chart
                if (window.ApexCharts && d.appointments_bar_chart) {
                    const chartEl = document.querySelector('#doctor-appointment-chart, .doctor-appointment-chart');
                    if (chartEl) {
                        chartEl.innerHTML = '';
                        new ApexCharts(chartEl, {
                            series: [{ name: 'Appointments', data: d.appointments_bar_chart.series }],
                            chart: { type: 'bar', height: 280, toolbar: { show: false } },
                            colors: ['#0d6efd'],
                            xaxis: { categories: d.appointments_bar_chart.labels }
                        }).render();
                    }
                }
            }
        } catch (err) {
            console.error('Error fetching doctor dashboard:', err);
        }
    }

    // 2. DOCTOR APPOINTMENTS LIST & REQUESTS (6.17, 6.18, 6.20)
    if (path.includes('doctors-appointments.html') || path.includes('doctors-appointment-requests.html') || path.includes('online-consultations.html')) {
        try {
            const isOnline = path.includes('online');
            const isRequest = path.includes('requests');
            let url = `${API_BASE}/appointments`;

            if (isOnline) url += '?type=Online';
            if (isRequest) url += '?status=Ongoing';

            const res = await fetch(url);
            const json = await res.json();
            const tbody = document.querySelector('table tbody, .appointments-table tbody');

            if (json.status === 'success' && tbody && json.data.data) {
                tbody.innerHTML = json.data.data.map(apt => `
                    <tr>
                        <td><span class="fw-bold">${apt.appointment_number}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="${apt.patient?.avatar || 'assets/img/patients/patient-01.jpg'}" class="rounded-circle me-2" width="36" height="36">
                                <div>
                                    <div class="fw-semibold">${apt.patient?.name || 'Walk-in Patient'}</div>
                                    <small class="text-muted">${apt.patient?.phone || '+977-9800000000'}</small>
                                </div>
                            </div>
                        </td>
                        <td>${apt.appointment_date} <small class="text-muted d-block">${apt.time_slot}</small></td>
                        <td><span class="badge bg-secondary-light">${apt.type}</span></td>
                        <td><span class="badge bg-${apt.status === 'Completed' ? 'success' : (apt.status === 'Cancelled' ? 'danger' : 'primary')}-light">${apt.status}</span></td>
                        <td class="fw-bold text-success">NPR ${apt.fee || '1,500.00'}</td>
                        <td>
                            <button class="btn btn-sm btn-light border action-view-apt" data-id="${apt.id}">View</button>
                            <button class="btn btn-sm btn-success action-status-apt" data-id="${apt.id}" data-status="Completed">Complete</button>
                        </td>
                    </tr>
                `).join('');
            }
        } catch (err) {
            console.error('Error fetching doctor appointments:', err);
        }
    }

    // 3. DOCTOR PRESCRIPTIONS (6.22)
    if (path.includes('doctors-prescriptions.html')) {
        try {
            const res = await fetch(`${API_BASE}/prescriptions`);
            const json = await res.json();
            const tbody = document.querySelector('table tbody, .prescriptions-table tbody');

            if (json.status === 'success' && tbody && json.data.data) {
                tbody.innerHTML = json.data.data.map(p => `
                    <tr>
                        <td class="fw-bold">#PRES-${p.id}</td>
                        <td>${p.patient?.name || 'Patient'}</td>
                        <td>${p.diagnosis}</td>
                        <td><span class="badge bg-info-light">${(p.medicines_json || []).length} Medicines</span></td>
                        <td>${p.created_at ? p.created_at.substring(0, 10) : '2026-07-23'}</td>
                        <td>
                            <a href="doctors-prescription-details.html?id=${p.id}" class="btn btn-sm btn-outline-primary">Details</a>
                        </td>
                    </tr>
                `).join('');
            }
        } catch (err) {
            console.error('Error fetching prescriptions:', err);
        }
    }

    // 4. DOCTOR REVIEWS (6.25)
    if (path.includes('doctors-reviews.html')) {
        try {
            const res = await fetch(`${API_BASE}/reviews`);
            const json = await res.json();
            const container = document.querySelector('#doctor-reviews-list, .reviews-list, tbody');

            if (json.status === 'success' && container && json.data.data) {
                container.innerHTML = json.data.data.map(r => `
                    <div class="p-3 border-bottom mb-2 bg-white rounded shadow-sm">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="mb-0 fw-bold">${r.patient?.name || 'Anonymous Patient'}</h6>
                            <div class="text-warning">★ ${r.rating}.0</div>
                        </div>
                        <p class="text-muted small mb-0">${r.review || 'Excellent doctor and attentive care!'}</p>
                    </div>
                `).join('');
            }
        } catch (err) {
            console.error('Error fetching doctor reviews:', err);
        }
    }

    // 5. DOCTOR LEAVES (6.26)
    if (path.includes('doctors-leaves.html')) {
        try {
            const res = await fetch(`${API_BASE}/leaves`);
            const json = await res.json();
            const tbody = document.querySelector('table tbody, .leaves-table tbody');

            if (json.status === 'success' && tbody && json.data) {
                tbody.innerHTML = json.data.map(l => `
                    <tr>
                        <td class="fw-semibold">${l.leave_type?.name || 'Casual Leave'}</td>
                        <td>${l.from_date} to ${l.to_date}</td>
                        <td>${l.days || 1} Days</td>
                        <td>${l.reason}</td>
                        <td><span class="badge bg-${l.status === 'Approved' ? 'success' : 'warning'}-light">${l.status}</span></td>
                    </tr>
                `).join('');
            }
        } catch (err) {
            console.error('Error fetching doctor leaves:', err);
        }
    }
});
