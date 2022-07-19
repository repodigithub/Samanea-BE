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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telphone');
            $table->string('team_leader')->nullable();
            $table->string('supervisor')->nullable();
            $table->index(['team_leader', 'supervisor']);
            $table->enum('level',['sales', 'team_leader', 'supervisor', 'manager']);
            $table->enum('status',['rejected', 'approved'])->default('rejected');
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
        Schema::dropIfExists('users');
    }
};
