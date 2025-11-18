<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaDesaToDesasTable extends Migration
{
    public function up()
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->string('nama_desa')->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn('nama_desa');
        });
    }
}
