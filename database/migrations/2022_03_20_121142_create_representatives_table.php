<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepresentativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->string('image',255);
            $table->string('name');
            $table->string('phone_code');
            $table->string('phone')->unique();
            $table->unsignedBigInteger('nationality_id');
            $table->foreign(columns: 'nationality_id')->references('id')
                ->on('nationalities')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('town_id');
            $table->foreign('town_id')->references('id')
                ->on('towns')->onDelete('cascade')->onUpdate('cascade');
            $table->string('residence_number');
            $table->integer('delivery_range');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('representatives');
    }
}
