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
        Schema::create('target_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('target');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->integer('pencapaian')->default(0);
            $table->enum('status', ['on_progress', 'success', 'failed'])->default('on_progress');
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
        Schema::dropIfExists('target');
    }
};
