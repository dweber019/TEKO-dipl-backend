<?php

namespace App\Helpers;

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