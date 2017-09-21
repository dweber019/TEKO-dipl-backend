<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'note', 'user_id',
    ];

    /**
     * Get all of the owning noteable models.
     */
    public function noteable()
    {
        return $this->morphTo();
    }

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
