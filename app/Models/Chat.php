<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'message', 'read', 'sender_id', 'receiver_id',
    ];

    /**
     * Get the user (sender) that owns the chat.
     */
    public function sender()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the user (receiver) that owns the chat.
     */
    public function receiver()
    {
        return $this->belongsTo('App\Models\User');
    }

}
