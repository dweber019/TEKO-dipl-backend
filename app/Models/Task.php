<?php

namespace App\Models;

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
        return $this->belongsToMany('App\Models\User')
          ->withPivot(['done'])
          ->withTimestamps();
    }

    /**
     * Get all of the task's notes.
     */
    public function notes()
    {
        return $this->morphMany('App\Note', 'noteable');
    }

    /**
     * Get all of the task's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
