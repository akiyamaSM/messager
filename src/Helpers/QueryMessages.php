<?php
namespace Inani\Messager\Helpers;

use App\User;
use Inani\Messager\Message;

trait QueryMessages
{

    /**
     * Get the messages from the user|users.
     *
     * @param $query
     * @param User|array $from
     * @return mixed
     */
    public function scopeFrom($query, $from)
    {
        if($from instanceof User)
        {
            return $query->where('from_id', $from->id);
        }
        if( is_array($from))
        {
            return $query->whereIn('from_id', $from);
        }
    }

    /**
     * Get the messages sent to the user|users.
     *
     * @param $query
     * @param User|array $to
     * @return mixed
     */
    public function scopeTo($query, $to)
    {
        if($to instanceof User)
        {
            return $query->where('to_id', $to->id);
        }
        if( is_array($to))
        {
            return $query->whereIn('to_id', $to);
        }
    }


    /**
     * Get the messages sent to the user|users.
     *
     * @param $query
     * @param Message|array $messages
     * @return mixed
     */
    public function scopeSelect($query, $messages)
    {
        if($messages instanceof self)
        {
            return $query->where('id', $messages->id);
        }
        if( is_array($messages))
        {
            return $query->whereIn('id', $messages);
        }
    }

    /**
     * Get Messages that has been seen.
     *
     * @param $query
     * @return mixed
     */
    public function scopeSeen($query)
    {
        return $query->where('state', MessageHandler::READ);
    }

    /**
     * Get Messages that has not been seen yet.
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnSeen($query)
    {
        return $query->where('state', MessageHandler::AVAILABLE);
    }

    /**
     * Get Messages in the draft.
     *
     * @param $query
     * @return mixed
     */
    public function scopeInDraft($query)
    {
        return $query->where('state', MessageHandler::DRAFT);
    }

    /**
     * Read the selected Messages
     *
     * @param $query
     * @return mixed
     */
    public function scopeReadThem($query)
    {
        return $query->where('state', '!=', MessageHandler::DRAFT)
                     ->update(['state' => MessageHandler::READ]);
    }

    /**
     * Send the selected Messages
     *
     * @param $query
     * @return mixed
     */
    public function scopeSend($query)
    {
        return $query->whereNotNull('to_id')
                    ->update(['state' => MessageHandler::AVAILABLE]);
    }
}