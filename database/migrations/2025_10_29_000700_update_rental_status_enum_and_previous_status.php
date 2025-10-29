<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'previous_status')) {
                $table->string('previous_status')->nullable()->after('status');
            }
        });

        DB::statement("ALTER TABLE rentals MODIFY status ENUM('pending','active','completed','overdue') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rentals MODIFY status ENUM('pending','active','completed','cancelled','overdue','returned_early') NOT NULL DEFAULT 'pending'");

        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'previous_status')) {
                $table->dropColumn('previous_status');
            }
        });
    }
};
