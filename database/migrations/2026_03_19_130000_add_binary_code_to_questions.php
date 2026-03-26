<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('binary_code')->nullable()->after('password');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('binary_code');
        });
    }
};
