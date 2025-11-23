<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text(column: 'terms_ar')->nullable();
            $table->text(column: 'terms_en')->nullable();
            $table->text(column: 'privacy_ar')->nullable();
            $table->text(column: 'privacy_en')->nullable();
            $table->text(column: 'about_us_ar')->nullable();
            $table->text(column: 'about_us_en')->nullable();
            $table->string(column: 'facebook')->nullable();
            $table->string(column: 'insta')->nullable();
            $table->string(column: 'twitter')->nullable();
            $table->string(column: 'snapchat')->nullable();
            $table->text(column: 'user_info')->nullable();
            $table->text(column: 'provider_info')->nullable();
            $table->text(column: 'image')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
