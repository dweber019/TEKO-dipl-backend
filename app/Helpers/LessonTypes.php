<?php

namespace App\Helpers;


class LessonTypes
{

    const LESSON = 'lesson';
    const EXAM = 'exam';
    const REMINDER = 'reminder';

    public static function toArray() {
        return [
          LessonTypes::LESSON,
          LessonTypes::EXAM,
          LessonTypes::REMINDER,
        ];
    }

}