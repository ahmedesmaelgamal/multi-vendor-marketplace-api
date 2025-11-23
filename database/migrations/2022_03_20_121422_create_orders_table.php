<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->nullOnDelete();

            $table->unsignedBigInteger('address_id')->nullable();
            $table->foreign('address_id')->references('id')
                ->on('addresses')->nullOnDelete();

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->nullOnDelete();

            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')
                ->on('categories')->nullOnDelete();

            $table->text('note')->nullable();
            $table->string('pin')->nullable();
            $table->enum('status',['new','offered','accepted','rejected','preparing','on_way','delivered'])
                ->default('new')->nullable();

            $table->double('total_price')->default(0)->nullable();

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
        Schema::dropIfExists('orders');
    }
}
