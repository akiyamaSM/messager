<?php


namespace Inani\Messager\Helpers;


use Inani\Messager\Message;
use App\User;

class MessageHandler {

    const DRAFT = 0;
    const AVAILABLE = 1;
    const READ = 2;

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