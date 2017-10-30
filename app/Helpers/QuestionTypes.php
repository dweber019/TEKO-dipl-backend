<?php

namespace App\Helpers;


/**
 * Helper class for task item question types for type safety
 *
 * Class QuestionTypes
 * @package App\Helpers
 */
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