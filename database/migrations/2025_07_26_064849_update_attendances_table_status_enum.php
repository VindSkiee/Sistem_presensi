<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', ['present', 'alpa'])->default('alpa')->change();
            $table->boolean('is_validated')->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->enum('status', ['present', 'alpa'])->change(); // default dihapus saat rollback
            $table->boolean('is_validated')->change(); // default dihapus saat rollback
        });
    }
};
