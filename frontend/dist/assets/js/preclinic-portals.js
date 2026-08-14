/**
 * NepXMedica Patient, Nurse, Receptionist, Pharmacist, Admin, Queue & Clinical Portals Live Data Integrator (Phases 7 - 13)
 */

document.addEventListener('DOMContentLoaded', async () => {
    const path = window.location.pathname.toLowerCase();

    // 1. PATIENT DASHBOARD (7.17)
    if (path.includes('patient-dashboard.html')) {
        try {
            const res = await fetch('/api/v1/patient/dashboard');
            const json = await res.json();
            if (json.status === 'success') {
                const d = json.data;
                const upEl = document.querySelector('[data-stat="upcoming_count"], .upcoming-count');
                if (upEl) upEl.innerText = d.upcoming_count;

                const presEl = document.querySelector('[data-stat="prescriptions_count"], .prescriptions-count');
                if (presEl) presEl.innerText = d.prescriptions_count;

                const billEl = document.querySelector('[data-stat="total_billed"], .total-billed');
                if (billEl) billEl.innerText = `NPR ${d.total_billed}`;

                const docEl = document.querySelector('[data-stat="doctors_count"], .doctors-count');
                if (docEl) docEl.innerText = d.doctors_count;
            }
        } catch(e) { console.error('Patient dashboard error:', e); }
    }

    // 2. NURSE DASHBOARD (8.09)
    if (path.includes('nurse-dashboard.html')) {
        try {
            const res = await fetch('/api/v1/nurse/dashboard');
            const json = await res.json();
            if (json.status === 'success') {
                const d = json.data;
                const pEl = document.querySelector('[data-stat="patients_under_care"], .patients-under-care');
                if (pEl) pEl.innerText = d.patients_under_care;

                const cEl = document.querySelector('[data-stat="critical_alerts"], .critical-alerts-count');
                if (cEl) cEl.innerText = d.critical_alerts_count;

                const mEl = document.querySelector('[data-stat="medications_due"], .medications-due');
                if (mEl) mEl.innerText = d.medications_due;
            }
        } catch(e) { console.error('Nurse dashboard error:', e); }
    }

    // 3. RECEPTIONIST DASHBOARD (9.09)
    if (path.includes('receptionist')) {
        if (path.includes('receptionist-dashboard.html')) {
            try {
                const res = await fetch('/api/v1/receptionist/dashboard');
                const json = await res.json();
                if (json.status === 'success') {
                    const d = json.data;
                    const tEl = document.querySelector('[data-stat="today_appointments"], .today-appointments');
                    if (tEl) tEl.innerText = d.today_appointments;
                }
            } catch(e) { console.error('Receptionist dashboard error:', e); }
        }
    }

    // 4. PHARMACIST PORTAL (Phase 10)
    if (path.includes('pharmacist') || path.includes('order-history.html')) {
        if (path.includes('pharmacist-dashboard.html')) {
            try {
                const res = await fetch('/api/v1/pharmacist/dashboard');
                const json = await res.json();
                if (json.status === 'success') {
                    const d = json.data;
                    const mEl = document.querySelector('[data-stat="total_medicines"], .total-medicines');
                    if (mEl) mEl.innerText = d.total_medicines;
                }
            } catch(e) { console.error('Pharmacist dashboard error:', e); }
        }
    }

    // 5. CLINICAL SYSTEM (Phase 13: 13.07 - 13.12)
    if (path.includes('lab') || path.includes('imaging') || path.includes('clinical')) {
        // Lab Tests (13.07)
        if (path.includes('lab-tests.html')) {
            try {
                const res = await fetch('/api/v1/lab/tests');
                const json = await res.json();
                const tbody = document.querySelector('table tbody');
                if (json.status === 'success' && tbody && json.data.data) {
                    tbody.innerHTML = json.data.data.map(t => `
                        <tr>
                            <td class="fw-bold">#LAB-${t.id}</td>
                            <td>${t.patient?.name || 'Patient'}</td>
                            <td>${t.test_name}</td>
                            <td><span class="badge bg-secondary-light">${t.category}</span></td>
                            <td>${t.reported_at ? t.reported_at.substring(0, 10) : '2026-07-23'}</td>
                            <td><span class="badge bg-success-light">${t.status}</span></td>
                        </tr>
                    `).join('');
                }
            } catch(e) { console.error('Lab tests error:', e); }
        }

        // Lab Reports (13.08)
        if (path.includes('lab-reports.html')) {
            try {
                const res = await fetch('/api/v1/lab/reports');
                const json = await res.json();
                const tbody = document.querySelector('table tbody');
                if (json.status === 'success' && tbody && json.data.data) {
                    tbody.innerHTML = json.data.data.map(r => `
                        <tr>
                            <td class="fw-bold">#REP-${r.id}</td>
                            <td>${r.patient?.name || 'Patient'}</td>
                            <td>${r.test_name}</td>
                            <td><span class="badge bg-success-light">Completed</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1">View</button>
                                <button class="btn btn-sm btn-outline-success">Print PDF</button>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch(e) { console.error('Lab reports error:', e); }
        }
    }
});
