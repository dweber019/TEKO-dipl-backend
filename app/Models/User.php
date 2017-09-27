<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\Helpers\UserTypes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'auth0_id', 'invite_token', 'invite_email', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'invite_token', 'auth0_id', 'invite_email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $model->invite_token = md5($model->firstname . $model->lastname . uniqid());
            $model->calender_token = md5($model->firstname . $model->lastname . uniqid());
        });
    }


    public function isNotStudent() {
        return $this->type !== UserTypes::STUDENT;
    }

    /**
     * The groups that belong to the user.
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group')
          ->withTimestamps();
    }

    /**
     * The subjects that belong to the user.
     */
    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject')
          ->withTimestamps();
    }

    /**
     * The tasks that belong to the user.
     */
    public function tasks()
    {
        return $this->belongsToMany('App\Models\Task')
          ->withPivot(['done'])
          ->withTimestamps();
    }

    /**
     * The task items that belong to the user.
     */
    public function taskItems()
    {
        return $this->belongsToMany('App\Models\TaskItem')
          ->withPivot(['result'])
          ->withTimestamps();
    }

    /**
     * Get the notes for the user.
     */
    public function notes()
    {
        return $this->hasMany('App\Models\Note');
    }

    /**
     * The grades that belong to the user.
     */
    public function grades()
    {
        return $this->belongsToMany('App\Models\Subject', 'grades')
          ->withPivot(['id', 'grade'])
          ->withTimestamps();
    }

    /**
     * Get the comments for the user.
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    /**
     * The notifications that belong to the user.
     */
    public function notifications()
    {
        return $this->belongsToMany('App\Models\Notification')
          ->withPivot(['id', 'read'])
          ->withTimestamps();
    }

    /**
     * Get the chats (as sender) for the user.
     */
    public function senderChat()
    {
        return $this->hasMany('App\Models\Chat', 'sender_id');
    }

    /**
     * Get the chats (as receiver) for the user.
     */
    public function receiverChat()
    {
        return $this->hasMany('App\Models\Chat', 'receiver_id');
    }
}
