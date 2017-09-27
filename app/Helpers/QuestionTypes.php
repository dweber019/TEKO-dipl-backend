<?php
/**
 * Created by PhpStorm.
 * User: tzhweda9
 * Date: 26.09.17
 * Time: 11:39
 */

namespace App\Helpers;


class QuestionTypes
{

    const TOGGLE = 'toggle';
    const SELECT = 'select';
    const FILE = 'file';
    const INPUT = 'input';
    const TEXT = 'text';

    public static function toArray() {
        return [
          QuestionTypes::TOGGLE,
          QuestionTypes::SELECT,
          QuestionTypes::FILE,
          QuestionTypes::INPUT,
          QuestionTypes::TEXT
        ];
    }

}