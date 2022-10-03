<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->text('description');
            $table->integer('seats');
            $table->boolean('toilet');
            $table->boolean('supply');
            $table->boolean('socket');
            $table->boolean('climate');
            $table->boolean('wifi');
            $table->boolean('tv');
            $table->boolean('vip');
            $table->boolean('ecology');
            $table->enum('status', ['Active', 'Archive']);
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
        Schema::dropIfExists('buses');
    }
};
