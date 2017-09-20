<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersNotesLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_notes_lessons', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
              ->on('users')->onDelete('cascade');

            $table->unsignedInteger('lesson_id');
            $table->foreign('lesson_id')->references('id')
              ->on('lessons')->onDelete('cascade');

            $table->primary(['user_id', 'lesson_id']);

            $table->text('note');

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
        Schema::dropIfExists('users_notes_lessons');
    }
}
