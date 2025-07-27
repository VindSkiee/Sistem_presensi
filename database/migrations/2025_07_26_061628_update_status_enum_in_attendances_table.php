<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('unvalidated', 'validated', 'alpa') NOT NULL DEFAULT 'unvalidated'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY status ENUM('unvalidated', 'validated') NOT NULL DEFAULT 'unvalidated'");
    }
};
