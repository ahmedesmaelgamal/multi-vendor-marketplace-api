<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fake_name');
            $table->string('email')->unique()->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable()->cascadeOnDelete();
            $table->foreign('nationality_id')->references('id')->on('nationalities')->nullOnDelete();
            $table->unsignedBigInteger('town_id')->nullable()->cascadeOnDelete();
            $table->foreign('town_id')->references('id')->on('towns')->nullOnDelete();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password');
            $table->string('vat_number')->nullable();
            $table->string('image')->nullable();
            $table->string('record')->nullable();
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
        Schema::dropIfExists('providers');
    }
}
