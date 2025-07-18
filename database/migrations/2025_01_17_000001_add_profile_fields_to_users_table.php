<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\UserRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role system
            $table->enum('role', UserRole::getValues())->default(UserRole::USER->value)->after('email');
            
            // Contact information
            $table->string('phone')->nullable()->after('email');
            $table->string('county')->nullable()->after('phone');
            
            // Donor-specific fields
            $table->string('organization')->nullable()->after('county');
            $table->text('address')->nullable()->after('organization');
            $table->string('profile_picture')->nullable()->after('address');
            
            // Preferences
            $table->boolean('newsletter_subscribed')->default(false)->after('profile_picture');
            $table->boolean('email_notifications')->default(true)->after('newsletter_subscribed');
            $table->boolean('sms_notifications')->default(false)->after('email_notifications');
            
            // Donation preferences
            $table->json('preferred_causes')->nullable()->after('sms_notifications');
            $table->string('preferred_payment_method')->nullable()->after('preferred_causes');
            
            // Admin fields
            $table->timestamp('last_login_at')->nullable()->after('preferred_payment_method');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_active')->default(true)->after('last_login_ip');
            
            // Soft deletes for user data retention compliance
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'county',
                'organization',
                'address',
                'profile_picture',
                'newsletter_subscribed',
                'email_notifications',
                'sms_notifications',
                'preferred_causes',
                'preferred_payment_method',
                'last_login_at',
                'last_login_ip',
                'is_active',
                'deleted_at'
            ]);
        });
    }
};