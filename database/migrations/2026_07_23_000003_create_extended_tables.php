<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Roles & Permissions
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, doctor, patient, nurse, receptionist, pharmacist
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('module');
            $table->string('action'); // view, create, edit, delete
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // Users get a role
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('active');
        });

        // Patient Vitals
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('heart_rate', 5, 1)->nullable();      // bpm
            $table->string('blood_pressure')->nullable();          // e.g. 120/80
            $table->decimal('oxygen_level', 5, 1)->nullable();    // %
            $table->decimal('weight', 6, 2)->nullable();          // lbs
            $table->decimal('height', 5, 2)->nullable();          // cm
            $table->decimal('temperature', 4, 1)->nullable();     // °F
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('pulse', 5, 1)->nullable();
            $table->string('status')->default('Normal');           // Normal, Monitor, Critical
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();
        });

        // Staff / Nurses / Receptionists
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('role'); // nurse, receptionist, pharmacist, lab_technician
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('designation')->nullable();
            $table->string('avatar')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->date('joining_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // HRM — Leaves
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days_allowed')->default(12);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->morphs('leavable'); // staff or doctor
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('days');
            $table->text('reason')->nullable();
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected
            $table->timestamps();
        });

        // HRM — Attendance
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->morphs('attendable'); // staff or doctor
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('status')->default('Present'); // Present, Absent, Late, Half Day
            $table->timestamps();
        });

        // HRM — Holidays
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('type')->default('Public'); // Public, Optional
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // HRM — Payroll
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->morphs('payrollable'); // staff or doctor
            $table->string('month'); // e.g. 2026-07
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->string('payment_status')->default('Pending'); // Pending, Paid
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });

        // Pharmacy — Medicines
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('category'); // Antibiotic, Analgesic, etc.
            $table->string('unit')->default('Tablet'); // Tablet, Capsule, Syrup, Injection
            $table->decimal('price', 8, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('reorder_level')->default(50);
            $table->date('expiry_date')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Pharmacy — Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Pharmacy — Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('Pending'); // Pending, Delivered, Cancelled
            $table->date('order_date');
            $table->date('expected_delivery')->nullable();
            $table->timestamps();
        });

        // Pharmacy — Stock Transfers
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->string('from_location');
            $table->string('to_location');
            $table->integer('quantity');
            $table->string('status')->default('Completed');
            $table->timestamp('transferred_at')->useCurrent();
            $table->timestamps();
        });

        // Finance — Expense Categories
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6c757d');
            $table->timestamps();
        });

        // Finance — Expenses
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->text('notes')->nullable();
            $table->string('status')->default('Paid');
            $table->timestamps();
        });

        // Finance — Income
        Schema::create('income', function (Blueprint $table) {
            $table->id();
            $table->string('source'); // Consultation, Lab Test, Pharmacy, etc.
            $table->decimal('amount', 10, 2);
            $table->date('income_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Lab Reports
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('test_name');
            $table->string('category')->default('Pathology'); // Pathology, Radiology, etc.
            $table->text('result')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('Pending'); // Pending, Completed
            $table->date('test_date');
            $table->timestamps();
        });

        // Notifications
        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable'); // user, doctor, patient
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('info'); // info, warning, success, danger
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Clinical Notes
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->text('note');
            $table->string('note_type')->default('General'); // General, Shift Handover, Critical
            $table->timestamps();
        });

        // Services & Specializations (for sidebar config pages)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('fee', 8, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specializations');
        Schema::dropIfExists('services');
        Schema::dropIfExists('clinical_notes');
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('lab_reports');
        Schema::dropIfExists('income');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('leaves');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('vitals');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
