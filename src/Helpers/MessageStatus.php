<?php

namespace Inani\Messager\Helpers;

use App\User;
use Inani\Messager\Message;

trait MessageStatus
{
    /**
     * Check if it can be set as draft.
     *
     * @return bool
     */
    public function canBeSetAsDraft()
    {
        if( is_null($this->state) || $this->isDraft())
        {
            return true;
        }
        return false;
    }

    /**
     * check if its already a draft
     *
     * @return bool
     */
    public function isDraft()
    {
        return $this->state == MessageHandler::DRAFT;
    }

    /**
     * Check if it can be read
     *
     * @return bool
     */
    public function canBeSetAsRead()
    {
        return $this->isAvailable();
    }

    /**
     * Check if its draft or already send
     *
     * @return bool
     */
    public function canBeSetAsSent()
    {
        return $this->isDraft() || $this->isAvailable();
    }

    /**
     * Check if its sent
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->state == MessageHandler::AVAILABLE;
    }

    /**
     * Set as Available
     *
     * @return $this
     */
    public function setAsRead()
    {
        if( $this->canBeSetAsRead())
        {
            $this->state = MessageHandler::READ;
        }
        return $this;
    }

    /**
     * Set as Available
     *
     * @return $this
     */
    public function setAsAvailable()
    {
        if($this->canBeSetAsSent())
        {
            $this->state = MessageHandler::AVAILABLE;
        }
        return $this;
    }

    /**
     * Set as Draft
     *
     * @return $this
     */
    public function setAsDraft()
    {
        if($this->canBeSetAsDraft())
        {
            $this->state = MessageHandler::DRAFT;
        }
        return $this;
    }

    /**
     * Set receiver of the message
     *
     * @param User $user
     */
    public function setReceiver(User $user)
    {
        $this->to_id = $user->id;
    }

    /**
     * Set receiver of the message By ID
     *
     * @param $id
     * @return $this
     */
    public function setReceiverById($id)
    {
        $this->to_id = $id;
        return $this;
    }

    /**
     * Set sender of the message
     *
     * @param User $user
     */
    public function setSender(User $user)
    {
        $this->from_id = $user->id;
    }

    /**
     * Check if the sender is set
     *
     * @return bool
     */
    public function hasSender()
    {
        if(! is_null($this->from_id))
        {
            return ! is_null(User::find($this->from_id));
        }
        return false;
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