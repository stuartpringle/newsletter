<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_campaign_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('newsletter_campaigns')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->nullable()->constrained('mailing_list_signups')->nullOnDelete();
            $table->foreignId('send_id')->nullable()->constrained('newsletter_campaign_sends')->nullOnDelete();
            $table->string('type');
            $table->string('url')->nullable();
            $table->string('message_id')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaign_events');
    }
};
