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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('departure');
            $table->string('destination');
            $table->string('driver_phones');
            $table->text('departure_days');
            $table->integer('bus_id');
            $table->string('departure_time');
            $table->integer('route_path_image_id');
            $table->text('route_time');
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
        Schema::dropIfExists('routes');
    }
};
