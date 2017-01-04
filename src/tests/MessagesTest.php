<?php

namespace Inani\Messager\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Inani\Messager\Helpers\MessageHandler;

class MessagesTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $sender;

    /**
     * @var User
     */
    protected $receiver;

    /** @test */
    public function it_asserts_true()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_sends_message()
    {
        $this->sender = User::find(19); // will be changed later
        $this->receiver = User::find(20); // will be changed later

        $data = [
            'content' => 'SomeContent',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        $this->assertEquals(1, $this->sender->sent()->count());
    }
}