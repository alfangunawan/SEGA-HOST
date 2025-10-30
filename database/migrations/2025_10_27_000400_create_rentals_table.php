<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('duration_days')->default(1);
            $table->enum('status', [
                'pending',
                'active',
                'completed',
                'overdue',
            ])->default('pending');
            $table->string('previous_status')->nullable();
            $table->decimal('total_cost', 12, 2);
            $table->decimal('penalty_cost', 12, 2)->nullable()->default(0);
            $table->decimal('final_settlement', 12, 2)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamp('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
