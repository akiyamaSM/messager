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

    /**
     * Check if the current message instance
     * is root of a conversation
     *
     * @return bool
     */
    public function isRoot()
    {
        return is_null($this->root_id);
    }

    /**
     * Get the root of conversation
     *
     * @return $this|\Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRootOfConversation()
    {
        if($this->isRoot())
        {
            return $this;
        }

        return $this->root;
    }

    /**
     * Assign the id of root
     *
     * @param Message $mayBeRoot
     * @return Message
     */
    public function setRoot(Message $mayBeRoot)
    {
        $this->root_id = $mayBeRoot->getRootOfConversation($mayBeRoot)->id;
        return $this;
    }
}
