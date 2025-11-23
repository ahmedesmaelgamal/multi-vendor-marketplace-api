<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title');
            $table->unsignedBigInteger('main_category_id');
            $table->foreign('main_category_id')->references('id')
                ->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->foreign('provider_id')->references('id')
                ->on('providers')->onDelete('cascade');
            $table->text('specifications');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('product_suggestions');
    }
}
