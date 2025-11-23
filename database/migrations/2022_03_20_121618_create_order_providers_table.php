<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')
                ->on('orders')->onDelete('cascade');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->onDelete('cascade');

            $table->enum('status',['new','hide','taken'])
                ->default('new')->nullable();

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
        Schema::dropIfExists('order_providers');
    }
}
