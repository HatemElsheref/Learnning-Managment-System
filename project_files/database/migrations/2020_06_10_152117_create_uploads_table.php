<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->integer('parent_id')->unsigned();        // id of model
            $table->string('parent');               //model name that has more files attached  ex courses or projects
            $table->string('mimes');    //pdf/image/word
            $table->boolean('status')->default(false);    //status published or not in front end
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
        Schema::dropIfExists('uploads');
    }
}
