<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // job, volunteer, internship
            $table->text('description');
            $table->longText('requirements');
            $table->longText('responsibilities');
            $table->string('location');
            $table->string('employment_type'); // full-time, part-time, contract, remote
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('KES');
            $table->date('application_deadline');
            $table->string('status')->default('active'); // active, paused, closed, filled
            $table->integer('positions_available')->default(1);
            $table->integer('applications_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes for fast queries
            $table->index(['status', 'application_deadline']);
            $table->index(['type', 'status']);
            $table->index(['location']);
            $table->index(['is_featured', 'status']);
            
            // Conditional fulltext index (only for MySQL/PostgreSQL)
            if (config('database.default') !== 'sqlite') {
                $table->fullText(['title', 'description']); // Search optimization
            }
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_listing_id')->constrained()->onDelete('cascade');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->text('cover_letter')->nullable();
            $table->string('cv_file_path');
            $table->json('additional_documents')->nullable(); // other file paths
            $table->json('responses')->nullable(); // custom application form responses
            $table->string('status')->default('submitted'); // submitted, reviewed, shortlisted, interviewed, offered, rejected
            $table->integer('rating')->nullable(); // 1-5 rating
            $table->text('notes')->nullable(); // HR notes
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Indexes for fast queries
            $table->index(['job_listing_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['applicant_email']);
            $table->index(['reviewed_by']);
        });

        Schema::create('application_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('job_applications')->onDelete('cascade');
            $table->string('old_status');
            $table->string('new_status');
            $table->text('comment')->nullable();
            $table->foreignId('changed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['application_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_history');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_listings');
    }
};