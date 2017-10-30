<?php

namespace App\Helpers;

/**
 * Helper class for user types for type safety
 *
 * Class UserTypes
 * @package App\Helpers
 */
class UserTypes
{

    const STUDENT = 'student';
    const TEACHER = 'teacher';
    const ADMIN = 'admin';

    public static function toArray() {
        return [
          UserTypes::STUDENT,
          UserTypes::TEACHER,
          UserTypes::ADMIN,
        ];
    }

}