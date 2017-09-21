<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'message', 'ref_id', 'ref',
    ];

    /**
     * The users that belong to the notification.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')
          ->withPivot(['id', 'read'])
          ->withTimestamps();
    }
}
