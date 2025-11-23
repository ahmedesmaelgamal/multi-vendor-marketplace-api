<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderOfferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_offer_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')
                ->on('orders')->onDelete('cascade');



            $table->unsignedBigInteger('order_offer_id')->nullable();
            $table->foreign('order_offer_id')->references('id')
                ->on('order_offers')->onDelete('cascade');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');

            $table->enum('type',['price','less','other'])
                ->default('price')->nullable();

            $table->double('price')->default(0);
            $table->double('available_qty')->default(0);

            $table->unsignedBigInteger('other_product_id')->nullable();
            $table->foreign('other_product_id')->references('id')
                ->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('order_offer_details');
    }
}
