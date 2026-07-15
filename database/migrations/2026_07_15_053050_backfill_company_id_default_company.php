<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // audit_logs stays nullable — Super Admin actions (e.g. login) aren't tied to any company.
    private array $requiredOnTables = ['members', 'deposits', 'expenses', 'ledger_entries'];

    private array $backfillOnlyTables = ['audit_logs'];

    public function up(): void
    {
        $companyId = DB::table('companies')->insertGetId([
            'name' => 'ATTONIRVOR',
            'slug' => 'attonirvor',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->update(['company_id' => $companyId]);

        foreach ($this->backfillOnlyTables as $table) {
            DB::table($table)->update(['company_id' => $companyId]);
        }

        foreach ($this->requiredOnTables as $table) {
            DB::table($table)->update(['company_id' => $companyId]);

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('company_id')->nullable(false)->change();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->requiredOnTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->unsignedBigInteger('company_id')->nullable()->change();
            });
        }

        DB::table('users')->update(['company_id' => null]);
        DB::table('companies')->where('slug', 'attonirvor')->delete();
    }
};
