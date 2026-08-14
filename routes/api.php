<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\V1\DoctorPortalController;
use App\Http\Controllers\Api\V1\PatientPortalController;
use App\Http\Controllers\Api\V1\NursePortalController;
use App\Http\Controllers\Api\V1\ReceptionistPortalController;
use App\Http\Controllers\Api\V1\PharmacistPortalController;
use App\Http\Controllers\Api\V1\ClinicOperationsController;
use App\Http\Controllers\Api\V1\QueueManagementController;
use App\Http\Controllers\Api\V1\ClinicalSystemController;
use App\Http\Controllers\Api\V1\AiAutomationController;
use App\Http\Controllers\Api\V1\HrmController;
use App\Http\Controllers\Api\V1\FinancialsController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\CmsSupportController;
use App\Http\Controllers\Api\V1\SuperAdminController;
use App\Http\Controllers\Api\V1\CommunicationController;
use App\Http\Controllers\Api\V1\UtilityController;

/*
|--------------------------------------------------------------------------
| API Routes — Preclinic Hospital & SaaS Platform
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Authentication Endpoints (4.02, 4.03, 4.05, 4.06)
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Admin Dashboard Endpoints (5.01 - 5.07)
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/appointments-chart', [DashboardController::class, 'appointmentsChart']);
    Route::get('/dashboard/popular-doctors', [DashboardController::class, 'popularDoctors']);
    Route::get('/dashboard/doctor-schedule-today', [DashboardController::class, 'doctorScheduleToday']);
    Route::get('/dashboard/top-patients', [DashboardController::class, 'topPatients']);
    Route::get('/dashboard/recent-transactions', [DashboardController::class, 'recentTransactions']);
    Route::get('/dashboard/leave-requests', [DashboardController::class, 'leaveRequests']);

    // Doctor Portal Endpoints (6.01 - 6.15)
    Route::prefix('doctor')->group(function () {
        Route::get('/dashboard', [DoctorPortalController::class, 'dashboard']);
        Route::get('/appointments', [DoctorPortalController::class, 'appointments']);
        Route::get('/appointments/{id}', [DoctorPortalController::class, 'showAppointment']);
        Route::post('/appointments', [DoctorPortalController::class, 'storeAppointment']);
        Route::put('/appointments/{id}', [DoctorPortalController::class, 'updateAppointment']);
        Route::get('/schedules', [DoctorPortalController::class, 'schedules']);
        Route::put('/schedules', [DoctorPortalController::class, 'updateSchedules']);
        Route::get('/prescriptions', [DoctorPortalController::class, 'prescriptions']);
        Route::get('/prescriptions/{id}', [DoctorPortalController::class, 'showPrescription']);
        Route::post('/prescriptions', [DoctorPortalController::class, 'storePrescription']);
        Route::get('/patients', [DoctorPortalController::class, 'patients']);
        Route::get('/patients/{id}', [DoctorPortalController::class, 'showPatient']);
        Route::get('/reviews', [DoctorPortalController::class, 'reviews']);
        Route::get('/leaves', [DoctorPortalController::class, 'leaves']);
        Route::post('/leaves', [DoctorPortalController::class, 'storeLeave']);
    });

    // Patient Portal Endpoints (7.01 - 7.36)
    Route::prefix('patient')->group(function () {
        Route::get('/dashboard', [PatientPortalController::class, 'dashboard']);
        Route::get('/appointments', [PatientPortalController::class, 'appointments']);
        Route::post('/appointments', [PatientPortalController::class, 'bookAppointment']);
        Route::get('/doctors', [PatientPortalController::class, 'doctors']);
        Route::get('/prescriptions', [PatientPortalController::class, 'prescriptions']);
        Route::get('/invoices', [PatientPortalController::class, 'invoices']);
        Route::get('/vitals', [PatientPortalController::class, 'vitals']);
        Route::post('/vitals', [PatientPortalController::class, 'storeVital']);
        Route::get('/health-reports', [PatientPortalController::class, 'healthReports']);
    });

    // Nurse Portal Endpoints (8.01 - 8.24)
    Route::prefix('nurse')->group(function () {
        Route::get('/dashboard', [NursePortalController::class, 'dashboard']);
        Route::get('/patients', [NursePortalController::class, 'patients']);
        Route::get('/vitals', [NursePortalController::class, 'vitals']);
        Route::post('/vitals', [NursePortalController::class, 'storeVital']);
        Route::get('/clinical-notes', [NursePortalController::class, 'clinicalNotes']);
        Route::post('/clinical-notes', [NursePortalController::class, 'storeClinicalNote']);
        Route::get('/critical-alerts', [NursePortalController::class, 'criticalAlerts']);
        Route::get('/queue', [NursePortalController::class, 'queue']);
    });

    // Receptionist Portal Endpoints (9.01 - 9.28)
    Route::prefix('receptionist')->group(function () {
        Route::get('/dashboard', [ReceptionistPortalController::class, 'dashboard']);
        Route::get('/appointments', [ReceptionistPortalController::class, 'appointments']);
        Route::post('/appointments', [ReceptionistPortalController::class, 'storeAppointment']);
        Route::get('/patients', [ReceptionistPortalController::class, 'patients']);
        Route::post('/patients', [ReceptionistPortalController::class, 'storePatient']);
        Route::get('/queue', [ReceptionistPortalController::class, 'queue']);
        Route::post('/queue/token', [ReceptionistPortalController::class, 'generateToken']);
        Route::get('/payments', [ReceptionistPortalController::class, 'payments']);
    });

    // Pharmacist Portal Endpoints (10.01 - 10.30)
    Route::prefix('pharmacist')->group(function () {
        Route::get('/dashboard', [PharmacistPortalController::class, 'dashboard']);
        Route::get('/medicines', [PharmacistPortalController::class, 'medicines']);
        Route::post('/medicines', [PharmacistPortalController::class, 'storeMedicine']);
        Route::get('/inventory/stock', [PharmacistPortalController::class, 'stock']);
        Route::get('/inventory/expiry-alerts', [PharmacistPortalController::class, 'expiryAlerts']);
        Route::get('/suppliers', [PharmacistPortalController::class, 'suppliers']);
        Route::post('/suppliers', [PharmacistPortalController::class, 'storeSupplier']);
        Route::get('/purchase-orders', [PharmacistPortalController::class, 'purchaseOrders']);
        Route::get('/transfers', [PharmacistPortalController::class, 'transfers']);
    });

    // Admin Clinic Operations Endpoints (11.01 - 11.38)
    Route::get('/doctors', [ClinicOperationsController::class, 'doctors']);
    Route::get('/doctors/{id}/slots', [ClinicOperationsController::class, 'doctorSlots']);
    Route::get('/doctors/{id}', [ClinicOperationsController::class, 'showDoctor']);
    Route::post('/doctors', [ClinicOperationsController::class, 'storeDoctor']);
    Route::put('/doctors/{id}', [ClinicOperationsController::class, 'updateDoctor']);
    Route::delete('/doctors/{id}', [ClinicOperationsController::class, 'destroyDoctor']);
    Route::get('/patients', [ClinicOperationsController::class, 'patients']);
    Route::get('/patients/{id}', [ClinicOperationsController::class, 'showPatient']);
    Route::post('/patients', [ClinicOperationsController::class, 'storePatient']);
    Route::put('/patients/{id}', [ClinicOperationsController::class, 'updatePatient']);
    Route::delete('/patients/{id}', [ClinicOperationsController::class, 'destroyPatient']);
    Route::get('/appointments', [ClinicOperationsController::class, 'appointments']);
    Route::get('/appointments/{id}', [ClinicOperationsController::class, 'showAppointment']);
    Route::post('/appointments', [ClinicOperationsController::class, 'storeAppointment']);
    Route::put('/appointments/{id}', [ClinicOperationsController::class, 'updateAppointment']);
    Route::delete('/appointments/{id}', [ClinicOperationsController::class, 'destroyAppointment']);
    Route::get('/specializations', [ClinicOperationsController::class, 'specializations']);
    Route::post('/specializations', [ClinicOperationsController::class, 'storeSpecialization']);
    Route::put('/specializations/{id}', [ClinicOperationsController::class, 'updateSpecialization']);
    Route::delete('/specializations/{id}', [ClinicOperationsController::class, 'destroySpecialization']);
    Route::get('/services', [ClinicOperationsController::class, 'services']);
    Route::post('/services', [ClinicOperationsController::class, 'storeService']);
    Route::put('/services/{id}', [ClinicOperationsController::class, 'updateService']);
    Route::delete('/services/{id}', [ClinicOperationsController::class, 'destroyService']);
    Route::get('/assets', [ClinicOperationsController::class, 'assets']);
    Route::post('/assets', [ClinicOperationsController::class, 'storeAsset']);
    Route::put('/assets/{id}', [ClinicOperationsController::class, 'updateAsset']);
    Route::get('/activities', [ClinicOperationsController::class, 'activities']);

    // Queue Management Endpoints (Phase 12: 12.01 - 12.17)
    Route::get('/queues', [QueueManagementController::class, 'queues']);
    Route::post('/queues/token', [QueueManagementController::class, 'storeToken']);
    Route::put('/queues/{id}/status', [QueueManagementController::class, 'updateStatus']);
    Route::get('/queues/waiting-list', [QueueManagementController::class, 'waitingList']);
    Route::get('/queues/analytics', [QueueManagementController::class, 'analytics']);
    Route::get('/queues/display', [QueueManagementController::class, 'displayBoard']);

    // Clinical System Endpoints (Phase 13: 13.01 - 13.12)
    Route::get('/lab/tests', [ClinicalSystemController::class, 'labTests']);
    Route::post('/lab/tests', [ClinicalSystemController::class, 'storeLabTest']);
    Route::get('/lab/reports', [ClinicalSystemController::class, 'labReports']);
    Route::get('/lab/reports/{id}', [ClinicalSystemController::class, 'showLabReport']);
    Route::post('/lab/reports/upload', [ClinicalSystemController::class, 'uploadLabReport']);
    Route::get('/lab/imaging', [ClinicalSystemController::class, 'imaging']);

    // AI & Automation Endpoints (Phase 14: 14.01 - 14.12)
    Route::post('/ai/chat', [AiAutomationController::class, 'chat']);
    Route::post('/ai/smart-diagnosis', [AiAutomationController::class, 'smartDiagnosis']);
    Route::get('/ai/diagnosis-sessions', [AiAutomationController::class, 'diagnosisSessions']);
    Route::get('/ai/model-accuracy-chart', [AiAutomationController::class, 'modelAccuracyChart']);
    Route::post('/ai/risk-prediction', [AiAutomationController::class, 'riskPrediction']);
    Route::post('/ai/auto-schedule', [AiAutomationController::class, 'autoSchedule']);
    Route::post('/ai/voice-notes', [AiAutomationController::class, 'voiceNotes']);

    // Admin Pharmacy & Inventory Aliases (Phase 15: 15.01 - 15.14)
    Route::get('/medicines', [PharmacistPortalController::class, 'medicines']);
    Route::post('/medicines', [PharmacistPortalController::class, 'storeMedicine']);
    Route::get('/inventory/stock', [PharmacistPortalController::class, 'stock']);
    Route::get('/inventory/expiry-alerts', [PharmacistPortalController::class, 'expiryAlerts']);
    Route::get('/suppliers', [PharmacistPortalController::class, 'suppliers']);
    Route::post('/suppliers', [PharmacistPortalController::class, 'storeSupplier']);
    Route::get('/purchase-orders', [PharmacistPortalController::class, 'purchaseOrders']);
    Route::get('/stock-transfers', [PharmacistPortalController::class, 'transfers']);

    // HRM Module Endpoints (Phase 16: 16.01 - 16.24)
    Route::get('/staff', [HrmController::class, 'staff']);
    Route::post('/staff', [HrmController::class, 'storeStaff']);
    Route::get('/departments', [HrmController::class, 'departments']);
    Route::get('/designations', [HrmController::class, 'designations']);
    Route::get('/attendance', [HrmController::class, 'attendance']);
    Route::get('/leaves', [HrmController::class, 'leaves']);
    Route::put('/leaves/{id}/approve', [HrmController::class, 'approveLeave']);
    Route::put('/leaves/{id}/reject', [HrmController::class, 'rejectLeave']);
    Route::get('/leave-types', [HrmController::class, 'leaveTypes']);
    Route::get('/holidays', [HrmController::class, 'holidays']);
    Route::get('/payroll', [HrmController::class, 'payroll']);

    // Financials & Finance Endpoints (Phase 17: 17.01 - 17.23)
    Route::get('/expenses', [FinancialsController::class, 'expenses']);
    Route::get('/expense-categories', [FinancialsController::class, 'expenseCategories']);
    Route::get('/income', [FinancialsController::class, 'income']);
    Route::get('/invoices', [FinancialsController::class, 'invoices']);
    Route::get('/invoices/{id}', [FinancialsController::class, 'showInvoice']);
    Route::get('/payments', [FinancialsController::class, 'payments']);
    Route::get('/transactions', [FinancialsController::class, 'transactions']);
    Route::get('/insurance-claims', [FinancialsController::class, 'insuranceClaims']);
    Route::get('/reports/income', [FinancialsController::class, 'incomeReport']);
    Route::get('/reports/expenses', [FinancialsController::class, 'expenseReport']);
    Route::get('/reports/profit-loss', [FinancialsController::class, 'profitLossReport']);

    // Administration Settings Endpoints (Phase 18: 18.01 - 18.36)
    Route::get('/settings/{module}', [SettingsController::class, 'getSettings']);
    Route::put('/settings/{module}', [SettingsController::class, 'updateSettings']);
    Route::get('/roles', [SettingsController::class, 'roles']);
    Route::get('/permissions', [SettingsController::class, 'permissions']);

    // Content Management Endpoints (Phase 19: 19.01 - 19.09)
    Route::get('/blogs', [CmsSupportController::class, 'blogs']);
    Route::get('/blog-categories', [CmsSupportController::class, 'blogCategories']);
    Route::get('/pages', [CmsSupportController::class, 'pages']);
    Route::get('/faqs', [CmsSupportController::class, 'faqs']);

    // Support / Helpdesk Endpoints (Phase 20: 20.01 - 20.07)
    Route::get('/tickets', [CmsSupportController::class, 'tickets']);
    Route::get('/contact-messages', [CmsSupportController::class, 'contactMessages']);

    // SaaS Super Admin Endpoints (Phase 21: 21.01 - 21.09)
    Route::prefix('superadmin')->group(function () {
        Route::get('/stats', [SuperAdminController::class, 'stats']);
        Route::get('/clinics', [SuperAdminController::class, 'clinics']);
        Route::post('/clinics', [SuperAdminController::class, 'storeClinic']);
        Route::put('/clinics/{id}/suspend', [SuperAdminController::class, 'suspendClinic']);
        Route::put('/clinics/{id}/activate', [SuperAdminController::class, 'activateClinic']);
        Route::get('/plans', [SuperAdminController::class, 'plans']);
    });

    // Communication Endpoints (Phase 22: 22.01 - 22.14)
    Route::get('/messages', [CommunicationController::class, 'messages']);
    Route::get('/messages/{id}', [CommunicationController::class, 'showConversation']);
    Route::post('/messages', [CommunicationController::class, 'sendMessage']);
    Route::get('/notifications', [CommunicationController::class, 'notifications']);
    Route::post('/notifications/{id}/read', [CommunicationController::class, 'markNotificationRead']);
    Route::post('/notifications/read-all', [CommunicationController::class, 'markAllNotificationsRead']);
    Route::get('/announcements', [CommunicationController::class, 'announcements']);

    // Utility & Productivity Endpoints (Phase 23: 23.01 - 23.20)
    Route::get('/todos', [UtilityController::class, 'todos']);
    Route::get('/notes', [UtilityController::class, 'notes']);
    Route::get('/calendar-events', [UtilityController::class, 'calendarEvents']);
    Route::get('/contacts', [UtilityController::class, 'contacts']);
    Route::get('/call-history', [UtilityController::class, 'callHistory']);
    Route::get('/reports/appointments', [UtilityController::class, 'appointmentReport']);
    Route::get('/reports/patients', [UtilityController::class, 'patientReport']);

    // Protected Authentication Endpoints (4.04, 4.07)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
