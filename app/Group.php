<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'name',
    ];

    /**
     * The users that belong to the group.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
          ->withTimestamps();
    }
}
