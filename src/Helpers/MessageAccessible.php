<?php

namespace Inani\Messager\Helpers;

use App\Http\Requests\Request;
use Inani\Messager\Message;
use App\User;

trait MessageAccessible
{
    protected $message;

    /**
     * Get all sent messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sent()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    /**
     * Get all received messages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function received()
    {
        return $this->hasMany(Message::class, 'to_id');
    }

    /**
     * Create from request the two objects.
     *
     * @param Request|array $attribute
     * @return array
     */
    public static function createFromRequest($attribute)
    {
        if($attribute instanceof Request)
        {
            return MessageHandler::create($attribute->all());
        }
        if(is_array($attribute))
        {
            return MessageHandler::create($attribute);
        }

    }

    /**
     * write the message
     *
     * @param Message $message
     * @return $this
     */
    public function writes(Message $message)
    {
        $this->message = $message;
        $this->message->setSender($this);
        return $this;
    }

    /**
     * set the receiver
     *
     * @param User $receiver
     * @return $this
     */
    public function to(User $receiver)
    {
        $this->message->setReceiver($receiver);
        return $this;
    }

    /**
     * Send the message To the user
     *
     * @return bool
     */
    public function send()
    {
        if($this->message->hasSender())
        {
            if($this->message->setAsAvailable()->save())
            {
                return $this->message;
            }
        }
        return false;
    }

    /**
     * Set Message as draft
     *
     * @return $this
     */
    public function draft()
    {
        if($this->message->canBeSetAsDraft())
        {
            $this->message->state = MessageHandler::DRAFT;
            return $this;
        }

        return null;
    }

    /**
     * Keep a message in the draft
     *
     * @return bool
     */
    public function keep()
    {
        if($this->message->isDraft())
        {
            if($this->message->save())
            {
                return $this->message;
            }
        }
        return false;
    }

    /**
     * Just take the id of the root
     *
     * @param Message $mayBeRoot
     * @return $this
     */
    public function responds(Message $mayBeRoot)
    {
        $this->message->setRoot($mayBeRoot);
        return $this;
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