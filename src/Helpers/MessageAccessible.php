<?php

namespace Inani\Messager\Helpers;

use App\Http\Requests\Request;
use Inani\Messager\Message;
use App\User;
use InvalidArgumentException;

trait MessageAccessible
{
    /*
     * Holding the message instance
     */
    protected $message;

    /*
    * Holding the message instance
    */
    protected $cc = [];

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
                array_diff($this->cc, [$this->message->to_id]);
                if(count($this->cc) > 0)
                {
                    foreach($this->cc as $to)
                    {
                        $newMessage = Message::copyFrom($this->message);
                        $newMessage->setReceiverById($to)->save();
                    }
                    unset($this->cc);
                    $this->cc = [];
                }
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
     * Add more receivers to the same message
     *
     * @param $attribute
     * @return $this
     * @throws InvalidArgumentException
     */
    public function cc($attribute)
    {
        if($attribute instanceof User)
        {
            $this->addOnlyIfNotExists($attribute->getKey());

            return $this;
        }

        if(is_array($attribute))
        {
            foreach($attribute as $value)
            {
                if(is_int($value)){
                    $this->addOnlyIfNotExists($value);
                }else if($value instanceof User){
                    $this->addOnlyIfNotExists($value->getKey());
                }
            }
            return $this;
        }

        throw new InvalidArgumentException;
    }

    /**
     * Add the id if not exists
     *
     * @param $value
     * @return bool
     */
    protected function addOnlyIfNotExists($value)
    {
        if(!in_array($value, $this->cc))
        {
            $this->cc[] = $value;
            return true;
        }

        return false;
    }
}