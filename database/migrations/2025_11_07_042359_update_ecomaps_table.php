<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ecomaps', function (Blueprint $table) {
            $table->unsignedBigInteger('desa_id')->nullable();
            $table->date('tanggal')->nullable();
            $table->integer('jumlah_organik')->default(0);
            $table->integer('jumlah_anorganik')->default(0);
            $table->integer('jumlah_residu')->default(0);
            $table->string('pengelolaan_organik')->nullable();
            $table->string('pengelolaan_anorganik')->nullable();
            $table->string('pengelolaan_residu')->nullable();

            // relasi ke tabel desa
            $table->foreign('desa_id')->references('id')->on('desas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ecomaps', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn([
                'desa_id',
                'tanggal',
                'jumlah_organik',
                'jumlah_anorganik',
                'jumlah_residu',
                'pengelolaan_organik',
                'pengelolaan_anorganik',
                'pengelolaan_residu',
            ]);
        });
    }
};
