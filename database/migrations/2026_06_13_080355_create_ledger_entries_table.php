<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['deposit', 'expense', 'adjustment']);
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('credit_amount', 12, 2)->default(0);
            $table->decimal('debit_amount', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->date('date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
