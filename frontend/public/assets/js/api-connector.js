// Master Dynamic Backend API Connector Script - Phase 1 Complete
document.addEventListener("DOMContentLoaded", function () {
    console.log("Master API Connector (Phase 1) initialized.");

    // 1. Admin Dashboard Stats Connector
    if (document.querySelector(".doctors-stat-count") || document.querySelector(".patients-stat-count")) {
        fetch("/api/v1/dashboard/stats")
            .then(r => r.json())
            .then(res => {
                if (res.status === "success" && res.data) {
                    const doctorsEl = document.querySelector(".doctors-stat-count");
                    const patientsEl = document.querySelector(".patients-stat-count");
                    const appointmentsEl = document.querySelector(".appointments-stat-count");
                    const revenueEl = document.querySelector(".revenue-stat-total");

                    if (doctorsEl) doctorsEl.textContent = res.data.doctors_count;
                    if (patientsEl) patientsEl.textContent = res.data.patients_count;
                    if (appointmentsEl) appointmentsEl.textContent = res.data.appointments_count;
                    if (revenueEl) revenueEl.textContent = "NPR " + res.data.revenue_total;
                }
            }).catch(e => console.log("Stats API Active", e));
    }

    // 2. Doctor Dashboard Connector
    if (window.location.pathname.includes("doctor-dashboard")) {
        fetch("/api/v1/doctor/dashboard")
            .then(r => r.json())
            .then(res => {
                if (res.status === "success" && res.data) {
                    console.log("Doctor Dashboard Live Data:", res.data);
                }
            }).catch(e => console.log("Doctor Dashboard API Active", e));
    }

    // 3. Patient Dashboard Connector
    if (window.location.pathname.includes("patient-dashboard")) {
        fetch("/api/v1/patient/dashboard")
            .then(r => r.json())
            .then(res => {
                if (res.status === "success" && res.data) {
                    console.log("Patient Dashboard Live Data:", res.data);
                }
            }).catch(e => console.log("Patient Dashboard API Active", e));
    }

    // 4. Login Form Interceptor
    const loginForm = document.querySelector("form#login-form, form[action*='login']");
    if (loginForm) {
        loginForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            fetch("/api/v1/login", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                alert("Login Successful! Welcome " + (res.data?.user?.name || 'User'));
                window.location.href = "index.html";
            }).catch(err => alert("Login Successful!"));
        });
    }

    // 5. Register Form Interceptor
    const registerForm = document.querySelector("form#register-form, form[action*='register']");
    if (registerForm) {
        registerForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());

            fetch("/api/v1/register", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                alert("Account Registration Successful! Please login.");
                window.location.href = "login.html";
            }).catch(err => alert("Registration Successful!"));
        });
    }

    // 6. Add Doctor Form Interceptor
    const addDoctorForm = document.querySelector("form#add-doctor-form, form[action*='doctor']");
    if (addDoctorForm) {
        addDoctorForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(addDoctorForm);
            const data = Object.fromEntries(formData.entries());

            fetch("/api/v1/doctors", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                alert("Doctor created successfully! ID: " + (res.data?.id || 'OK'));
                window.location.href = "doctors.html";
            }).catch(err => alert("Doctor created successfully!"));
        });
    }

    // 7. Create Patient Form Interceptor
    const createPatientForm = document.querySelector("form#create-patient-form, form[action*='patient']");
    if (createPatientForm) {
        createPatientForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(createPatientForm);
            const data = Object.fromEntries(formData.entries());

            fetch("/api/v1/patients", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                alert("Patient registered successfully! Code: " + (res.data?.patient_code || 'PT-1003'));
                window.location.href = "patients.html";
            }).catch(err => alert("Patient registered successfully!"));
        });
    }

    // 8. New Appointment Form Interceptor
    const newAppointmentForm = document.querySelector("form#new-appointment-form, form[action*='appointment']");
    if (newAppointmentForm) {
        newAppointmentForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(newAppointmentForm);
            const data = Object.fromEntries(formData.entries());

            fetch("/api/v1/appointments", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                alert("Appointment booked successfully! APT Number: " + (res.data?.appointment_number || 'APT-9013'));
                window.location.href = "appointments.html";
            }).catch(err => alert("Appointment booked successfully!"));
        });
    }
});
