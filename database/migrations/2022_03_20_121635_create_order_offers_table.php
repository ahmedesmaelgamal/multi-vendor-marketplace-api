<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_offers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')
                ->on('orders')->onDelete('cascade');

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->onDelete('cascade');

            $table->double('total_price')->default(0)->nullable();
            $table->enum('status',['new','accepted','rejected'])
                ->default('new')->nullable();

            $table->bigInteger('delivery_date_time')->default(0)->nullable();
            $table->text('note')->nullable();
            $table->string('total_tax')->nullable();
            $table->string('total_before_tax')->nullable();


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
        Schema::dropIfExists('order_offers');
    }
}
