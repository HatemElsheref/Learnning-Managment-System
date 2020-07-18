<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreareCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries',function (Blueprint $table){
            $table->id();
            $table->string('Iso')->unique();
            $table->string('Name');
            $table->string('Iso3')->nullable();
            $table->integer('NumCode')->nullable();
            $table->integer('PhoneCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries') ;
    }
}
