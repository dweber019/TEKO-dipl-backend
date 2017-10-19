<?php

namespace App\Models;

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'ref_id' => 'integer',
    ];

    /**
     * The users that belong to the notification.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User')
          ->withPivot(['id', 'read'])
          ->withTimestamps();
    }
}
