<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mailing_list_signups', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('email')->constrained('newsletter_tenants')->nullOnDelete();
            $table->foreignId('list_id')->nullable()->after('tenant_id')->constrained('newsletter_mailing_lists')->nullOnDelete();
        });

        // Backfill a default tenant and list for existing records if needed.
        $tenantId = DB::table('newsletter_tenants')->value('id');
        if (! $tenantId) {
            $tenantId = DB::table('newsletter_tenants')->insertGetId([
                'name' => 'Default',
                'slug' => 'default',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $listId = DB::table('newsletter_mailing_lists')->where('tenant_id', $tenantId)->value('id');
        if (! $listId) {
            $listId = DB::table('newsletter_mailing_lists')->insertGetId([
                'tenant_id' => $tenantId,
                'name' => 'Default',
                'slug' => 'default',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('mailing_list_signups')
            ->whereNull('tenant_id')
            ->update([
                'tenant_id' => $tenantId,
                'list_id' => $listId,
            ]);
    }

    public function down(): void
    {
        Schema::table('mailing_list_signups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('list_id');
            $table->dropConstrainedForeignId('tenant_id');
        });
    }
};
