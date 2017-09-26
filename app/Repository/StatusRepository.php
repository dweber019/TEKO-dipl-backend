<?php

namespace App\Repository;

use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Task;

class StatusRepository
{

    public static function getStatusOfSubjects($subjects)
    {
        $subjectWithStatus = collect($subjects)->map(function($subject) {
            return StatusRepository::getStatusOfSubject($subject);
        });

        return $subjectWithStatus;
    }

    public static function getStatusOfSubject(Subject $subject)
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

        $subject->setAttribute('status', $status);

        return $subject;
    }

    public static function getStatusOfLessons($lessons)
    {
        $lessonsWithStatus = collect($lessons)->map(function($lesson) {
            return StatusRepository::getStatusOfLesson($lesson);
        });

        return $lessonsWithStatus;
    }

    public static function getStatusOfLesson(Lesson $lesson)
    {
        $status = true;

        $lessonTemp = $lesson->toArray();

        foreach ($lessonTemp['tasks'] as &$task) {
            if (empty($task['users'])) {
                $status = false;
                break;
            }

            if ($task['users'][0]['pivot']['done'] === 0) {
                $status = false;
                break;
            }
        }

        $lesson->setAttribute('status', $status);

        return $lesson;
    }

    public static function getStatusOfTasks($tasks)
    {
        $tasksWithStatus = collect($tasks)->map(function($task) {
            return StatusRepository::getStatusOfTask($task);
        });

        return $tasksWithStatus;
    }

    public static function getStatusOfTask(Task $task)
    {
        $status = true;

        $taskTemp = $task->toArray();

        if (empty($taskTemp['users'])) {
            $status = false;
        } else if ($taskTemp['users'][0]['pivot']['done'] === 0) {
            $status = false;
        }

        $task->setAttribute('status', $status);

        return $task;
    }

}