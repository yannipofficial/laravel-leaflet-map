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
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cat');
            $table->integer('likes')->default('0');
            $table->unsignedBigInteger('userID');
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('lat',20,15);
            $table->double('long',20,15);
            $table->timestamps();

            $table->foreign('cat')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('userID')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
};
