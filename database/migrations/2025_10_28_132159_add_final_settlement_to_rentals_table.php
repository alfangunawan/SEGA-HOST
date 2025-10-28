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
        Schema::table('rentals', function (Blueprint $table) {
            $table->decimal('final_settlement', 10, 2)->nullable()->after('penalty_cost');
            $table->boolean('is_paid')->default(false)->after('final_settlement');
            $table->timestamp('payment_date')->nullable()->after('is_paid');
            $table->string('payment_method')->nullable()->after('payment_date');
            $table->string('payment_reference')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['final_settlement', 'is_paid', 'payment_date', 'payment_method', 'payment_reference']);
        });
    }
};
