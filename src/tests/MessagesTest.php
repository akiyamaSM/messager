<?php

namespace Inani\Messager\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Inani\Messager\Helpers\MessageHandler;
use Inani\Messager\Message;

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
    public function it_sends_message()
    {
        $this->makeUsers();

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

    /** @test */
    public function it_saves_message_in_draft()
    {
        $this->makeUsers();

        $data = [
            'content' => 'SomeContent',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender->writes($message)
                     ->to($receiver)
                     ->draft()
                     ->keep();

        $this->assertEquals(1, $this->sender->sent()->inDraft()->count());
    }

    /** @test */
    public function he_checks_his_unseen_messages()
    {
        $this->makeUsers();

        $data = [
            'content' => 'SomeContent',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        // check there is a non read message
        $this->assertEquals(
            1, $this->receiver->received()->from($this->sender)->unSeen()->count()
        );
    }

    /** @test */
    public function he_reads_unseen_messages()
    {
        $this->makeUsers();

        $data = [
            'content' => 'SomeContent',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        $updated = $this->receiver->received()
                        ->from($this->sender)
                        ->unSeen()
                        ->readThem();

        $this->assertEquals(1, $updated);
    }

    /** @test */
    public function he_keeps_in_draft_without_receiver()
    {
        $this->makeUsers();

        $data = [
            'content' => 'SomeContent',
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $instance = $this->sender->writes($message)
                             ->draft()
                             ->keep();

        // check if the returned value is a Message
        $this->assertTrue($instance instanceof Message);
    }

    /** @test */
    public function he_reads_specific_message()
    {
        $this->makeUsers();

        $data = [
            'content' => 'SomeContent',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        $updated = $this->receiver->received()
                                  ->select($message)
                                  ->readThem();
        $this->assertEquals(1, $updated);
    }

    /** @test */
    public function user_responds_in_the_conversation()
    {
        $this->makeUsers();

        $data = [
            'content' => 'Root of the conversation',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        $res = new Message( ['content' => 'a response']);

        $this->receiver->writes($res)
            ->to($this->sender)
            ->responds($message)
            ->send();

        $this->assertEquals(1, $message->conversation()->count());
    }


    public function it_deletes_messages_from_the_interface_of_a_user()
    {
        $this->makeUsers();

        $data = [
            'content' => 'Root of the conversation',
            'to_id' => $this->receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->sender
            ->writes($message)
            ->to($receiver)
            ->send();

        Message::select($message)->delete($this->sender);

        $this->assertEquals(1, Message::deleted($this->sender));

        $this->assertEquals(0, Message::deleted($this->receiver));
    }
    /**
     * it creates two users
     *
     */
    public function makeUsers()
    {
        $this->sender = factory(User::class)->create();
        $this->receiver = factory(User::class)->create();
    }
}