

    // Appointment Calendar Data 

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        var statusColors = {
            'Pending': { bg: '#F4F4F5', text: '#6B7280', badge: 'bg-soft-secondary text-secondary' },
            'Confirmed': { bg: '#EAF7EF', text: '#27AE60', badge: 'bg-soft-success text-success' },
            'Checked In': { bg: '#FEFBF0', text: '#B7871A', badge: 'bg-soft-warning text-warning' },
            'Checked Out': { bg: '#EFF6FE', text: '#2F80ED', badge: 'bg-soft-info text-info' },
            'Cancelled': { bg: '#FDEEEE', text: '#EF1E1E', badge: 'bg-soft-danger text-danger' }
        };

        var appointments = [
            { patient: 'Michael Reyes', patientImg: 'assets/img/profiles/avatar-10.jpg', doctor: 'Dr. Mick Thompson', department: 'Cardiology', date: '2026-07-06', time: '10:00 AM', mode: 'In-Person', status: 'Pending' },
            { patient: 'Sophia Turner', patientImg: 'assets/img/profiles/avatar-08.jpg', doctor: 'Dr. Sarah Johnson', department: 'Dermatology', date: '2026-07-08', time: '11:30 AM', mode: 'Online', status: 'Pending' },
            { patient: 'Daniel Carter', patientImg: 'assets/img/profiles/avatar-02.jpg', doctor: 'Dr. Emily Carter', department: 'Pediatrics', date: '2026-07-09', time: '09:00 AM', mode: 'In-Person', status: 'Confirmed' },
            { patient: 'Olivia Bennett', patientImg: 'assets/img/profiles/avatar-04.jpg', doctor: 'Dr. Mick Thompson', department: 'Cardiology', date: '2026-07-10', time: '02:00 PM', mode: 'Online', status: 'Confirmed' },
            { patient: 'Ethan Brooks', patientImg: 'assets/img/profiles/avatar-07.jpg', doctor: 'Dr. Sarah Johnson', department: 'Dermatology', date: '2026-07-06', time: '10:30 AM', mode: 'In-Person', status: 'Checked In' },
            { patient: 'Ava Mitchell', patientImg: 'assets/img/profiles/avatar-09.jpg', doctor: 'Dr. Emily Carter', department: 'Pediatrics', date: '2026-07-13', time: '12:00 PM', mode: 'In-Person', status: 'Checked In' },
            { patient: 'Lucas Reid', patientImg: 'assets/img/profiles/avatar-03.jpg', doctor: 'Dr. Mick Thompson', department: 'Cardiology', date: '2026-07-03', time: '03:00 PM', mode: 'Online', status: 'Checked Out' },
            { patient: 'Grace Coleman', patientImg: 'assets/img/profiles/avatar-01.jpg', doctor: 'Dr. Sarah Johnson', department: 'Dermatology', date: '2026-07-02', time: '04:00 PM', mode: 'In-Person', status: 'Cancelled' },
            { patient: 'Henry Walsh', patientImg: 'assets/img/profiles/avatar-05.jpg', doctor: 'Dr. Emily Carter', department: 'Pediatrics', date: '2026-07-16', time: '05:30 PM', mode: 'Online', status: 'Cancelled' }
        ];

        var events = appointments.map(function (apt) {
            var colors = statusColors[apt.status];
            return {
                title: apt.patient,
                start: apt.date,
                backgroundColor: colors.bg,
                borderColor: colors.bg,
                textColor: colors.text,
                extendedProps: apt
            };
        });

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            views: {
                dayGridMonth: { buttonText: 'month' },
                timeGridWeek: { buttonText: 'week' },
                timeGridDay: { buttonText: 'day' }
            },
            initialView: 'dayGridMonth',
            height: 650,
            dayMaxEvents: true,
            navLinks: true,
            editable: true,
            events: events,
            eventContent: function (arg) {
                var apt = arg.event.extendedProps;
                return {
                    html:
                        '<div class="d-flex align-items-center px-1 py-1" style="overflow:hidden;">' +
                        '<img src="' + apt.patientImg + '" class="rounded-circle me-1" style="width:18px;height:18px;object-fit:cover;">' +
                        '<span class="fs-12 fw-medium text-truncate">' + apt.patient + '</span>' +
                        '<span class="fs-11 ms-1 text-truncate">· ' + apt.time + '</span>' +
                        '</div>'
                };
            },
            eventClick: function (info) {
                var apt = info.event.extendedProps;
                var colors = statusColors[apt.status];
                document.getElementById('apt_patient_img').src = apt.patientImg;
                document.getElementById('apt_patient_name').innerText = apt.patient;
                document.getElementById('apt_doctor').innerText = apt.doctor;
                document.getElementById('apt_department').innerText = apt.department;
                document.getElementById('apt_date').innerText = apt.date;
                document.getElementById('apt_time').innerText = apt.time;
                document.getElementById('apt_mode_badge').innerText = apt.mode;

                var statusBadge = document.getElementById('apt_status_badge');
                statusBadge.innerText = apt.status;
                statusBadge.className = 'badge fs-12 rounded-pill ' + colors.badge;

                var modalEl = document.getElementById('appointment_details_modal');
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        });
        calendar.render();
    });