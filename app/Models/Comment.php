<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'message', 'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'user_id' => 'integer',
    ];

    /**
     * Get all of the owning commentable models.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
