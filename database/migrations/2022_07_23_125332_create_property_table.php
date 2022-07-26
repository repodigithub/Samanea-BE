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
        Schema::create('property', function (Blueprint $table) {
            $table->id();
            $table->string('judul_project');
            $table->string('slider');
            $table->string('foto_overview');
            $table->string('judul_overview');
            $table->string('deskripsi_overview');
            $table->string('icon');
            $table->string('judul_icon');
            $table->string('deskripsi_icon');
            $table->string('foto_arsitek');
            $table->string('judul_arsitek');
            $table->string('deskripsi_arsitek');
            $table->string('foto_fasilitas');
            $table->string('judul_fasilitas');
            $table->string('deskiprsi_fasilitas');
            $table->string('link');
            $table->string('foto_masterplan');
            $table->string('judul_masterplan');
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
            $table->string('foto_footer');
            $table->string('deskripsi_footer');
            $table->longtext('alamat_footer');
            $table->string('link_maps');
            $table->enum('sosmed', ['facebook', 'twitter', 'ig']);
            $table->string('link_sosmed');
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
        Schema::dropIfExists('property');
    }
};
