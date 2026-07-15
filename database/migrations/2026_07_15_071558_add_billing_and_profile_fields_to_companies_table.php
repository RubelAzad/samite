<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('subscription_package_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->date('subscription_ends_at')->nullable()->after('subscription_package_id');
            $table->string('logo')->nullable()->after('address');
            $table->string('icon')->nullable()->after('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_package_id');
            $table->dropColumn(['subscription_ends_at', 'logo', 'icon']);
        });
    }
};
