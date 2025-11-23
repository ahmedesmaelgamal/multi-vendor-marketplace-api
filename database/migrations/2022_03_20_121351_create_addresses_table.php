<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->string('admin_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();

            $table->unsignedBigInteger('time_id')->nullable();
            $table->foreign('time_id')->references('id')
                ->on('delivery_times')->onDelete('cascade');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('default')->nullable();

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
        Schema::dropIfExists('addresses');
    }
}
