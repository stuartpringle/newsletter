<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_subscriber_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('mailing_list_signups')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('newsletter_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['subscriber_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriber_tag');
    }
};
