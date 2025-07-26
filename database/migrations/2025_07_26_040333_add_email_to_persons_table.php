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
        Schema::table('persons', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('name');
        });
    }

    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
