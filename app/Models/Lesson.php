<?php

namespace App\Models;

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
      'start_date', 'end_date', 'location', 'room', 'canceled', 'subject_id', 'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'subject_id' => 'integer',
      'canceled' => 'boolean',
    ];

    /**
     * Get the subject that owns the lesson.
     */
    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    /**
     * Get the tasks for the lesson.
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * Get all of the lesson's notes.
     */
    public function notes()
    {
        return $this->morphMany('App\Models\Note', 'noteable');
    }

    /**
     * Get all of the lesson's comments.
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }
}
