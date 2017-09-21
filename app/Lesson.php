<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
      'start_date', 'end_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'start_date', 'end_date', 'location', 'room', 'canceled',
    ];

    /**
     * Get the subject that owns the lesson.
     */
    public function subject()
    {
        return $this->belongsTo('App\Subject');
    }

    /**
     * Get the tasks for the lesson.
     */
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    /**
     * The user note that belong to the lesson.
     */
    public function userNote()
    {
        return $this->belongsToMany('App\User', 'users_notes_lessons')
          ->withPivot(['note'])
          ->withTimestamps();
    }

    /**
     * The user comments that belong to the lesson.
     */
    public function userComments()
    {
        return $this->belongsToMany('App\User', 'users_comments_lessons')
          ->withPivot(['id', 'message'])
          ->withTimestamps();
    }
}
