<?php

namespace Inani\Messager;

use Inani\Messager\Helpers\MessageStatus;
use Inani\Messager\Helpers\QueryMessages;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use MessageStatus, QueryMessages;

    protected $fillable = [
        'from_id', 'to_id', 'content', 'state', 'root_id'
    ];


    /**
     * Get the conversation Messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversation()
    {
        return $this->hasMany(Message::class, 'root_id');
    }

    /**
     * Get the parent of a message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function root()
    {
        return $this->belongsTo(Message::class, 'root_id', 'id');
    }

}
