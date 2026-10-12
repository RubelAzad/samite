<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE ledger_entries MODIFY COLUMN type ENUM('deposit', 'expense', 'adjustment', 'withdrawal') NOT NULL DEFAULT 'deposit'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ledger_entries MODIFY COLUMN type ENUM('deposit', 'expense', 'adjustment') NOT NULL DEFAULT 'deposit'");
    }
};
