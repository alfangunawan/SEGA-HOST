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
        Schema::create('configuration_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('configuration_profile_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('label');
            $table->string('key');
            $table->string('type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['configuration_profile_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuration_fields');
    }
};
