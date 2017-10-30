<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'note', 'user_id',
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
