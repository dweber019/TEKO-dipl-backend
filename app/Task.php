<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'due_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'description', 'due_date',
    ];

    /**
     * Get the lesson that owns the Task.
     */
    public function lesson()
    {
        return $this->belongsTo('App\Lesson');
    }

    /**
     * Get the task items for the task.
     */
    public function taskItems()
    {
        return $this->hasMany('App\TaskItem');
    }

    /**
     * The users that belong to the task.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
          ->withPivot(['done'])
          ->withTimestamps();
    }

    /**
     * The user note that belong to the task.
     */
    public function userkNote()
    {
        return $this->belongsToMany('App\User', 'users_notes_tasks')
          ->withPivot(['note'])
          ->withTimestamps();
    }

    /**
     * The user comments that belong to the task.
     */
    public function userComments()
    {
        return $this->belongsToMany('App\User', 'users_comments_tasks')
          ->withPivot(['id', 'message'])
          ->withTimestamps();
    }
}
