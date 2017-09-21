<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'auth0_id', 'invite_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'invite_token', 'auth0_id',
    ];

    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')
          ->withTimestamps();
    }

    /**
     * The subjects that belong to the user.
     */
    public function subjects()
    {
        return $this->belongsToMany('App\Subject')
          ->withTimestamps();
    }

    /**
     * The tasks that belong to the user.
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Task')
          ->withPivot(['done'])
          ->withTimestamps();
    }

    /**
     * The task items that belong to the user.
     */
    public function taskItems()
    {
        return $this->belongsToMany('App\TaskItem')
          ->withPivot(['result'])
          ->withTimestamps();
    }

    /**
     * The lesson note that belong to the user.
     */
    public function lessonNote()
    {
        return $this->belongsToMany('App\Lesson', 'users_notes_lessons')
          ->withPivot(['note'])
          ->withTimestamps();
    }

    /**
     * The task note that belong to the user.
     */
    public function taskNote()
    {
        return $this->belongsToMany('App\Task', 'users_notes_tasks')
          ->withPivot(['note'])
          ->withTimestamps();
    }

    /**
     * The grades that belong to the user.
     */
    public function grades()
    {
        return $this->belongsToMany('App\Subject', 'grades')
          ->withPivot(['id', 'grade'])
          ->withTimestamps();
    }

    /**
     * The lesson comments that belong to the user.
     */
    public function lessonComments()
    {
        return $this->belongsToMany('App\Lesson', 'users_comments_lessons')
          ->withPivot(['id', 'message'])
          ->withTimestamps();
    }

    /**
     * The task comments that belong to the user.
     */
    public function taskComments()
    {
        return $this->belongsToMany('App\Task', 'users_comments_tasks')
          ->withPivot(['id', 'message'])
          ->withTimestamps();
    }

    /**
     * The notifications that belong to the user.
     */
    public function notifications()
    {
        return $this->belongsToMany('App\Notification')
          ->withPivot(['id', 'read'])
          ->withTimestamps();
    }

    /**
     * Get the chats (as sender) for the user.
     */
    public function senderChat()
    {
        return $this->hasMany('App\Chat', 'sender_id');
    }

    /**
     * Get the chats (as receiver) for the user.
     */
    public function receiverChat()
    {
        return $this->hasMany('App\Chat', 'receiver_id');
    }
}
