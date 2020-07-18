<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_files', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned();
            $table->string('path');
            $table->text('shared')->nullable();
            $table->enum('status',['opened','closed'])->default('closed');
            $table->enum('type',['tma','mta','final'])->default('tma');
            $table->year('year');
            $table->enum('term',['spring','summer','fall'])->default('spring');
            $table->enum('hosting',['local','cloud','drive'])->default('cloud');   // for hosting type
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
        Schema::dropIfExists('course_files');
    }
}
