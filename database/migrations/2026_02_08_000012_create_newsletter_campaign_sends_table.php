<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_campaign_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('newsletter_campaigns')->cascadeOnDelete();
            $table->foreignId('subscriber_id')->constrained('mailing_list_signups')->cascadeOnDelete();
            $table->string('message_id')->nullable();
            $table->string('status')->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'subscriber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaign_sends');
    }
};
