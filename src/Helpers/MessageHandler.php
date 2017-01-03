<?php


namespace Inani\Messager\Helpers;


use Inani\Messager\Message;
use App\User;

class MessageHandler {

    const DRAFT = 0;
    const AVAILABLE = 1;
    const READ = 2;

    /**
     * Get An array and return two objects
     *
     * @param array $attribute
     * @return array
     */
    public  static function create(array $attribute)
    {
        return [
            new Message(
                [
                    'content' => $attribute['content'],
                ]
            ),
            User::findOrFail($attribute['to_id'])
        ];
    }
}