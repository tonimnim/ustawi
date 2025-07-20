<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('career_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('job'); // job or volunteer
            $table->string('department')->nullable();
            $table->string('location');
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'volunteer'])->default('full-time');
            $table->text('description');
            $table->text('requirements');
            $table->text('responsibilities');
            $table->string('salary_range')->nullable();
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_listings');
    }
};