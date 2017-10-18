<?php

namespace App\Repository;

use App\Models\Lesson;
use App\Models\Notification;
use App\Models\Subject;
use App\Models\Task;
use App\Models\User;

class NotificationRepository
{

    /**
     * Notify when user added to subject
     *
     * @param Subject $subject
     * @param User $user
     */
    public static function userAddedToSubject(Subject $subject, User $user)
    {
        NotificationRepository::createNotification($subject, [ $user->id ], 'subject-added', null);
    }


    /**
     * Notify when user removed from subject
     *
     * @param Subject $subject
     * @param User $user
     */
    public static function userRemovedToSubject(Subject $subject, User $user)
    {
        NotificationRepository::createNotification($subject, [ $user->id ], 'subject-removed', null);
    }

    /**
     * Notify when when lesson added
     *
     * @param Lesson $lesson
     */
    public static function lessonAdded(Lesson $lesson)
    {
        $secondaryEntity = $lesson->subject()->first();

        $userIds = collect($secondaryEntity->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($lesson, $userIds, 'lesson-added', $secondaryEntity);
    }

    /**
     * Notify when lesson removed
     *
     * @param Lesson $lesson
     */
    public static function lessonRemoved(Lesson $lesson)
    {
        $secondaryEntity = $lesson->subject()->first();

        $userIds = collect($secondaryEntity->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($lesson, $userIds, 'lesson-removed', $secondaryEntity);
    }

    /**
     * Notify when lesson canceled
     *
     * @param Lesson $lesson
     */
    public static function lessonCanceled(Lesson $lesson)
    {
        $secondaryEntity = $lesson->subject()->first();

        $userIds = collect($secondaryEntity->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($lesson, $userIds, 'lesson-canceled', $secondaryEntity);
    }

    /**
     * Notify when lesson uncanceled
     *
     * @param Lesson $lesson
     */
    public static function lessonUncanceled(Lesson $lesson)
    {
        $secondaryEntity = $lesson->subject()->first();

        $userIds = collect($secondaryEntity->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($lesson, $userIds, 'lesson-uncanceled', $secondaryEntity);
    }

    /**
     * Notify when a chat is added
     *
     * @param User $sender
     * @param $receiverId
     */
    public static function chatAdded(User $sender, $receiverId)
    {
        NotificationRepository::createNotification($sender, [ $receiverId ], 'chat', null);
    }

    /**
     * Notify when grade added
     *
     * @param Subject $subject
     * @param User $user
     */
    public static function gradeAdded(Subject $subject, User $user)
    {
        NotificationRepository::createNotification($subject, [ $user->id ], 'grade-added', null);
    }

    /**
     * Notify when grade removed
     *
     * @param Subject $subject
     * @param User $user
     */
    public static function gradeRemoved(Subject $subject, User $user)
    {
        NotificationRepository::createNotification($subject, [ $user->id ], 'grade-removed', null);
    }

    /**
     * Notify when comment to lesson added
     *
     * @param Lesson $lesson
     * @param User $user
     */
    public static function lessonCommentAdded(Lesson $lesson, User $user)
    {
        $secondaryEntity = $lesson->subject()->first();

        $userIds = collect($secondaryEntity->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($lesson, $userIds, 'lesson-comment', $user);
    }

    /**
     * Notify when comment to task added
     *
     * @param Task $task
     * @param User $user
     */
    public static function taskCommentAdded(Task $task, User $user)
    {
        $userIds = collect($task->lesson()->first()->subject()->first()->users()->get())->map(function($item) { return $item->id; } )->values()->all();

        NotificationRepository::createNotification($task, $userIds, 'task-comment', $user);
    }

    /**
     * Generate a notification
     *
     * @param $entity
     * @param array $userIds
     * @param $messageId
     * @param $secondaryEntity
     */
    private static function createNotification($entity, Array $userIds, $messageId, $secondaryEntity)
    {
        $notification = new Notification(NotificationRepository::getNotificationByEntity($messageId, $entity, $secondaryEntity));
        $notification->save();

        $notification->users()->syncWithoutDetaching($userIds);
    }

    /**
     * Get notification by entity
     *
     * @param $identification
     * @param $entity
     * @param $secondaryEntity
     * @return array
     */
    private static function getNotificationByEntity($identification, $entity, $secondaryEntity)
    {
        switch ($identification) {
            case 'subject-added':
                return NotificationRepository::buildNotification($entity->id, 'subject', trans('notification.' . $identification) . $entity->name);
            case 'subject-removed':
                return NotificationRepository::buildNotification($entity->id, 'subject', trans('notification.' . $identification) . $entity->name);
            case 'lesson-added':
                return NotificationRepository::buildNotification($entity->id, 'lesson', trans('notification.' . $identification) . $secondaryEntity->name);
            case 'lesson-removed':
                return NotificationRepository::buildNotification($secondaryEntity->id, 'subject', trans('notification.' . $identification) . $secondaryEntity->name);
            case 'lesson-canceled':
                return NotificationRepository::buildNotification($entity->id, 'lesson', trans('notification.' . $identification) . $secondaryEntity->name);
            case 'lesson-uncanceled':
                return NotificationRepository::buildNotification($entity->id, 'lesson', trans('notification.' . $identification) . $secondaryEntity->name);
            case 'chat':
                return NotificationRepository::buildNotification(null, 'chat', trans('notification.' . $identification) . $entity->firstname);
            case 'grade-added':
                return NotificationRepository::buildNotification($entity->id, 'subject', trans('notification.' . $identification) . $entity->name);
            case 'grade-removed':
                return NotificationRepository::buildNotification($entity->id, 'subject', trans('notification.' . $identification) . $entity->name);
            case 'lesson-comment':
                return NotificationRepository::buildNotification($entity->id, 'lesson', trans('notification.' . $identification) . $secondaryEntity->firstname);
            case 'task-comment':
                return NotificationRepository::buildNotification($entity->id, 'task', trans('notification.' . $identification) . $secondaryEntity->firstname);
        }
    }

    /**
     * Build the notification payload
     *
     * @param $refId
     * @param $refType
     * @param $message
     * @return array
     */
    private static function buildNotification($refId, $refType, $message)
    {
        return [ 'ref_id' => $refId, 'ref' => $refType, 'message' => $message ];
    }

}