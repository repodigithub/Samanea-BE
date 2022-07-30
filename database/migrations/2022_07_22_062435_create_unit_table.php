<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id();
            $table->string('foto_unit');
            $table->string('nama_unit');
            $table->foreignId('cluster_id')->constrained('cluster')->onUpdate('cascade')->onDelete('cascade');
            $table->string('deskripsi_unit');
            $table->string('luas_bangunan');
            $table->string('luas_tanah');
            $table->integer('kamar_tidur');
            $table->string('karpot');
            $table->integer('kamar_mandi');
            $table->string('galery_unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit');
    }
};
