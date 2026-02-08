<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('mailing_list_signups', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->enum('status', ['unconfirmed', 'subscribed', 'unsubscribed'])->default('unconfirmed');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mailing_list_signups');
    }
};
