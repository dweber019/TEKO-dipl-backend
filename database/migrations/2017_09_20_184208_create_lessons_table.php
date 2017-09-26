<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Helpers\LessonTypes;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('type', LessonTypes::toArray())->default(LessonTypes::LESSON);
            $table->string('location')->nullable();
            $table->string('room')->nullable();
            $table->boolean('canceled')->default(false);

            $table->unsignedInteger('subject_id');
            $table->foreign('subject_id')->references('id')
              ->on('subjects')->onDelete('cascade');

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
        Schema::dropIfExists('lessons');
    }
}
