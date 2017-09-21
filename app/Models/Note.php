<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasCompositePrimaryKey;

    /**
     * The primary key of the table.
     *
     * @var array
     */
    protected $primaryKey = ['user_id', 'noteable_id', 'noteable_type'];

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
