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
            $table->string('foto_arsitek');
            $table->string('judul_arsitek');
            $table->string('deskripsi_arsitek');
            $table->string('foto_fasilitas');
            $table->string('judul_fasilitas');
            $table->string('deskripsi_fasilitas');
            $table->string('link_fasilitas');
            $table->string('foto_masterplan');
            $table->string('judul_masterplan');
            $table->string('foto_footer');
            $table->string('deskripsi_footer');
            $table->longtext('alamat_footer');
            $table->string('link_maps');
            $table->enum('sosmed', ['facebook', 'twitter', 'ig', 'pinterest', 'linkedin', 'dribble', 'youtube', 'behance', 'vimeo-v']);
            $table->string('link_sosmed');
            $table->foreignId('buld_fasilitas_id')->constrained('buld_fasilitas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('unit')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('fasilitas_publik_id')->constrained('fasilitas_publik')->onUpdate('cascade')->onDelete('cascade');
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
