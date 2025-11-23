<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductsDontHaveProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_dont_have_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unique(['product_id', 'provider_id']);
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->nullOnDelete();
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->nullOnDelete();

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
        Schema::dropIfExists('products_dont_have_providers');
    }
}
