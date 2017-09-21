<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name', 'archived',
    ];

    /**
     * Get the lessons for the subject.
     */
    public function lessons()
    {
        return $this->hasMany('App\Lesson');
    }

    /**
     * The users that belong to the subject.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')
          ->withTimestamps();
    }

    /**
     * The user grades that belong to the subject.
     */
    public function userGrades()
    {
        return $this->belongsToMany('App\Models\User', 'grades')
          ->withPivot(['id', 'grade'])
          ->withTimestamps();
    }
}
