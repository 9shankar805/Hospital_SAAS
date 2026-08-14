<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Doctor Reviews (1.24)
        Schema::create('doctor_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->tinyInteger('rating')->default(5); // 1-5
            $table->text('review')->nullable();
            $table->string('status')->default('approved'); // approved, pending, rejected
            $table->timestamps();
        });

        // Insurance Claims (1.25)
        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->string('insurer_name');
            $table->string('claim_number')->unique();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('Pending'); // Pending, Approved, Rejected, Paid
            $table->timestamps();
        });

        // Assets (1.26)
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Equipment, Furniture, Vehicle, IT, etc.
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->string('location')->nullable();
            $table->string('status')->default('Active'); // Active, Maintenance, Disposed
            $table->timestamps();
        });

        // Extend queues table (1.27)
        Schema::table('queues', function (Blueprint $table) {
            $table->string('priority')->default('Normal')->after('status'); // Normal, Urgent, Emergency
            $table->text('notes')->nullable()->after('priority');
            $table->timestamp('called_at')->nullable()->after('notes');
            $table->timestamp('completed_at')->nullable()->after('called_at');
        });

        // Designations table (for HRM)
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Messages / Conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->morphs('sender');
            $table->text('body');
            $table->string('type')->default('text'); // text, email, sms
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Announcements
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('target_role')->default('all'); // all, doctor, patient, nurse, receptionist, pharmacist
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Todos
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->string('priority')->default('Normal'); // Low, Normal, High
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        // Notes
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('color')->default('#fff8e1');
            $table->timestamps();
        });

        // Contacts
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('role')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });

        // Tickets (Support)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->morphs('submitter');
            $table->string('subject');
            $table->text('description');
            $table->string('priority')->default('Medium'); // Low, Medium, High, Critical
            $table->string('status')->default('Open'); // Open, Pending, Resolved, Closed
            $table->timestamps();
        });

        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->morphs('replier');
            $table->text('message');
            $table->timestamps();
        });

        // Blogs (CMS)
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('author')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Settings (generic key-value store)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general');
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('todos');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('designations');
        Schema::table('queues', function (Blueprint $table) {
            $table->dropColumn(['priority', 'notes', 'called_at', 'completed_at']);
        });
        Schema::dropIfExists('assets');
        Schema::dropIfExists('insurance_claims');
        Schema::dropIfExists('doctor_reviews');
    }
};
