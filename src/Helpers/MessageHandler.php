<?php


namespace Inani\Messager\Helpers;


use Inani\Messager\Message;
use App\User;

class MessageHandler {

    // State of the Message
    const DRAFT = 0;
    const AVAILABLE = 1;
    const READ = 2;

    // Delete state of the Messages
    const NOT_DELETED = 0;
    const DELETED = 1;


    /**
     * Get an array and return two objects
     *
     * @param array $attribute
     * @return array
     */
    public  static function create(array $attribute)
    {
        $message = null;
        $user = null;

        if(array_key_exists('content', $attribute))
        {
            $message = new Message(['content' => $attribute['content']]);
        }

        if(array_key_exists('to_id', $attribute))
        {
            $user = User::find($attribute['to_id']);
        }

        return [$message , $user];
    }
}