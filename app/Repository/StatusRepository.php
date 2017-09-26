<?php

namespace App\Repository;

class StatusRepository
{

    public static function getStatusOfSubjects($subjects)
    {
        $subjectWithStatus = collect($subjects)->map(function($subject) {
            return StatusRepository::getStatusOfSubject($subject);
        });

        return $subjectWithStatus;
    }

    public static function getStatusOfSubject($subject)
    {
        $status = true;

        foreach ($subject->lessons->toArray() as &$lesson) {
            foreach ($lesson['tasks'] as &$task) {
                if (empty($task['users'])) {
                    $status = false;
                    break;
                }

                if ($task['users'][0]['pivot']['done'] === 0) {
                    $status = false;
                    break;
                }
            }
        };

        $subject['status'] = $status;

        return $subject;
    }

    public static function getStatusOfLessons($lessons)
    {
        $lessonsWithStatus = collect($lessons)->map(function($lesson) {
            return StatusRepository::getStatusOfLesson($lesson);
        });

        return $lessonsWithStatus;
    }

    public static function getStatusOfLesson($lesson)
    {
        $status = true;

        foreach ($lesson['tasks'] as &$task) {
            if (empty($task['users'])) {
                $status = false;
                break;
            }

            if ($task['users'][0]['pivot']['done'] === 0) {
                $status = false;
                break;
            }
        }

        $lesson['status'] = $status;

        return $lesson;
    }

    public static function getStatusOfTasks($tasks)
    {
        $tasksWithStatus = collect($tasks)->map(function($task) {
            return StatusRepository::getStatusOfTask($task);
        });

        return $tasksWithStatus;
    }

    public static function getStatusOfTask($task)
    {
        $status = true;

        if (empty($task['users'])) {
            $status = false;
        } else if ($task['users'][0]['pivot']['done'] === 0) {
            $status = false;
        }

        $task['status'] = $status;

        return $task;
    }

}