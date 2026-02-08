<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('newsletter_tenants')->cascadeOnDelete();
            $table->foreignId('list_id')->nullable()->constrained('newsletter_mailing_lists')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('newsletter_email_templates')->nullOnDelete();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->string('preview_text')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->longText('html')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
    }
};
