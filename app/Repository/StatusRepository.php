<?php

namespace App\Repository;

use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Task;

class StatusRepository
{

    /**
     * Calculate status for subjects
     *
     * @param Mixed $subjects
     * @return static
     */
    public static function getStatusOfSubjects($subjects)
    {
        $subjectWithStatus = collect($subjects)->map(function($subject) {
            return StatusRepository::getStatusOfSubject($subject);
        });

        return $subjectWithStatus;
    }

    /**
     * Calculate status of subject
     *
     * @param Subject $subject
     * @return Subject
     */
    public static function getStatusOfSubject(Subject $subject)
    {
        $status = 'done';

        if (empty($subject->lessons->toArray())) {
            $status = 'notask';
        } else {
            foreach ($subject->lessons->toArray() as &$lesson) {
                if (empty($lesson['tasks'])) {
                    $status = 'notask';
                } else {
                    foreach ($lesson['tasks'] as &$task) {
                        if (empty($task['users'])) {
                            $status = 'open';
                            break;
                        }

                        if ($task['users'][0]['pivot']['done'] === 0) {
                            $status = 'open';
                            break;
                        }
                    }
                }
            };
        }

        $subject->setAttribute('status', $status);

        return $subject;
    }

    /**
     * Calculate status of lessons
     *
     * @param $lessons
     * @return static
     */
    public static function getStatusOfLessons($lessons)
    {
        $lessonsWithStatus = collect($lessons)->map(function($lesson) {
            return StatusRepository::getStatusOfLesson($lesson);
        });

        return $lessonsWithStatus;
    }

    /**
     * Calculate status of lesson
     *
     * @param Lesson $lesson
     * @return Lesson
     */
    public static function getStatusOfLesson(Lesson $lesson)
    {
        $status = 'done';

        $lessonTemp = $lesson->toArray();

        if (empty($lessonTemp['tasks'])) {
            $status = 'notask';
        } else {
            foreach ($lessonTemp['tasks'] as &$task) {
                if (empty($task['users'])) {
                    $status = 'open';
                    break;
                }

                if ($task['users'][0]['pivot']['done'] === 0) {
                    $status = 'open';
                    break;
                }
            }
        }

        $lesson->setAttribute('status', $status);

        return $lesson;
    }

    /**
     * Calculate status of tasks
     *
     * @param $tasks
     * @return static
     */
    public static function getStatusOfTasks($tasks)
    {
        $tasksWithStatus = collect($tasks)->map(function($task) {
            return StatusRepository::getStatusOfTask($task);
        });

        return $tasksWithStatus;
    }

    /**
     * Calculate status of task
     *
     * @param Task $task
     * @return Task
     */
    public static function getStatusOfTask(Task $task)
    {
        $status = 'done';

        $taskTemp = $task->toArray();

        if (empty($taskTemp['users'])) {
            $status = 'open';
        } else if ($taskTemp['users'][0]['pivot']['done'] === 0) {
            $status = 'open';
        }

        $task->setAttribute('status', $status);

        return $task;
    }

}