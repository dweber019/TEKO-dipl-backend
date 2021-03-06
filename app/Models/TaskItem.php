<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'title', 'description', 'question_type', 'question', 'order', 'task_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'task_id' => 'integer',
    ];

    /**
     * Get the task that owns the task item.
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    /**
     * The users that belong to the task item.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')
          ->withPivot(['result'])
          ->withTimestamps();
    }
}
