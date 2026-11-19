<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email')->unique();
            $t->string('password'); $t->string('role', 10)->default('user');
            $t->boolean('is_active')->default(true); $t->rememberToken(); $t->timestamps();
        });
        Schema::create('password_reset_tokens', function (Blueprint $t) {
            $t->string('email')->primary(); $t->string('token'); $t->timestamp('created_at')->nullable();
        });
        Schema::create('sessions', function (Blueprint $t) {
            $t->string('id')->primary(); $t->foreignId('user_id')->nullable()->index();
            $t->string('ip_address', 45)->nullable(); $t->text('user_agent')->nullable();
            $t->longText('payload'); $t->integer('last_activity')->index();
        });
        Schema::create('organizational_units', function (Blueprint $t) {
            $t->id(); $t->string('name')->unique(); $t->timestamps();
        });
        Schema::create('employees', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $t->foreignId('organizational_unit_id')->nullable()->constrained()->nullOnDelete();
            $t->string('nip', 30)->unique(); $t->string('full_name');
            $t->string('employment_type', 20)->default('PNS');
            $t->string('rank', 130)->nullable(); $t->string('grade', 30)->nullable();
            $t->string('position'); $t->date('service_start_date')->nullable();
            $t->string('phone', 25)->nullable(); $t->text('leave_address')->nullable();
            $t->string('supervisor_name')->nullable(); $t->string('supervisor_nip', 30)->nullable();
            $t->string('supervisor_position')->nullable(); $t->timestamps();
        });
        Schema::create('leave_types', function (Blueprint $t) {
            $t->id(); $t->string('code', 32)->unique(); $t->string('name');
            $t->enum('count_mode', ['working', 'calendar'])->default('working');
            $t->boolean('uses_balance')->default(false); $t->boolean('requires_attachment')->default(false);
            $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('holidays', function (Blueprint $t) {
            $t->id(); $t->date('date')->unique(); $t->string('name'); $t->timestamps();
        });
        Schema::create('leave_balances', function (Blueprint $t) {
            $t->id(); $t->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $t->unsignedSmallInteger('year'); $t->integer('remaining_days')->default(0);
            $t->unique(['employee_id', 'year']); $t->timestamps();
        });
        Schema::create('leave_requests', function (Blueprint $t) {
            $t->id(); $t->string('request_number', 32)->unique();
            $t->foreignId('employee_id')->constrained()->restrictOnDelete();
            $t->foreignId('leave_type_id')->constrained()->restrictOnDelete();
            $t->date('start_date'); $t->date('end_date'); $t->unsignedInteger('duration_days');
            $t->text('reason'); $t->text('leave_address'); $t->string('contact_phone', 25);
            $t->string('status', 28)->default('submitted')->index();
            $t->text('revision_notes')->nullable(); $t->timestamp('submitted_at')->nullable();
            $t->string('letter_number')->nullable()->unique(); $t->timestamp('issued_at')->nullable();
            $t->json('employee_snapshot')->nullable(); $t->json('official_snapshot')->nullable();
            $t->string('signed_document_path')->nullable();
            $t->timestamps();
            $t->index(['employee_id','start_date','end_date']);
        });
        Schema::create('leave_decisions', function (Blueprint $t) {
            $t->id(); $t->foreignId('leave_request_id')->constrained()->cascadeOnDelete();
            $t->foreignId('recorded_by')->constrained('users')->restrictOnDelete();
            $t->string('stage', 16); $t->string('decision', 16);
            $t->string('official_name'); $t->string('official_nip',30)->nullable();
            $t->string('official_position'); $t->string('reference')->nullable();
            $t->text('notes')->nullable(); $t->date('decision_date'); $t->timestamps();
            $t->unique(['leave_request_id','stage']);
        });
        Schema::create('leave_balance_transactions', function (Blueprint $t) {
            $t->id(); $t->foreignId('leave_balance_id')->constrained()->restrictOnDelete();
            $t->foreignId('leave_request_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('actor_id')->constrained('users')->restrictOnDelete();
            $t->integer('delta'); $t->string('type', 32); $t->text('note'); $t->timestamps();
        });
        Schema::create('leave_attachments', function (Blueprint $t) {
            $t->id(); $t->foreignId('leave_request_id')->constrained()->cascadeOnDelete();
            $t->string('path'); $t->string('original_name'); $t->timestamps();
        });
        Schema::create('app_settings', function (Blueprint $t) {
            $t->string('key')->primary(); $t->text('value')->nullable();
        });
        Schema::create('document_sequences', function (Blueprint $t) {
            $t->unsignedSmallInteger('year')->primary(); $t->unsignedInteger('last_number')->default(0);
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id(); $t->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('action'); $t->string('subject_type'); $t->unsignedBigInteger('subject_id');
            $t->json('details')->nullable(); $t->timestamps();
            $t->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        foreach (['audit_logs','document_sequences','app_settings','leave_attachments','leave_balance_transactions',
            'leave_decisions','leave_requests','leave_balances','holidays','leave_types','employees',
            'organizational_units','sessions','password_reset_tokens','users'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
