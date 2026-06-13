<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('members')
            ->where(function ($q) {
                $q->whereNull('phone')->orWhere('phone', '');
            })
            ->update(['phone' => '01736625062']);
    }

    public function down(): void
    {
        DB::table('members')
            ->where('phone', '01736625062')
            ->update(['phone' => null]);
    }
};
