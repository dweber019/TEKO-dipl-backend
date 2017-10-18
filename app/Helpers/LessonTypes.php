<?php

namespace App\Helpers;


/**
 * Helper class for lesson types for type safety
 *
 * Class LessonTypes
 * @package App\Helpers
 */
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