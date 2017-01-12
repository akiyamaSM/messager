<?php

namespace Inani\Messager\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Inani\Messager\Helpers\MessageHandler;
use Inani\Messager\Tag;

class TagsTest extends \TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    /** @test */
    public function he_adds_a_new_tag_from_a_tag_instance()
    {
        $this->user = $this->makeUser();
        $tag = new Tag([
            'name' => 'primary',
            'color' => 'red'
        ]);
        $this->user->addNewTag($tag);
        $this->assertEquals(1, $this->user->tags()->count());
    }

    /** @test */
    public function he_adds_a_new_tag_from_a_tag_array()
    {
        $this->user = $this->makeUser();
        $tag = [
            'name' => 'primary',
            'color' => 'red'
        ];
        $this->user->addNewTag($tag);
        $this->assertEquals(1, $this->user->tags()->count());
    }

    /** @test */
    public function it_throws_exception_when_he_adds_a_new_tag_from_invalid_argument()
    {
        try{
            $this->user = $this->makeUser();
            $this->user->addNewTag(1);
        }catch (\Exception $e){
            $this->assertTrue($e instanceof \InvalidArgumentException);
        }
        $this->assertNotNull($e);
    }

    /** @test */
    public function he_modifies_the_tag_info()
    {
        $this->user = $this->makeUser();
        $tag = new Tag([
            'name' => 'primary',
            'color' => 'red'
        ]);
        $this->user->addNewTag($tag);
        $this->assertEquals("primary", $this->user->tags()->first()->name);
        $this->assertEquals("red", $this->user->tags()->first()->color);

        $this->user->tag($tag)->name("social")->color("blue")->apply();

        $this->assertEquals("social", $this->user->tags()->first()->name);
        $this->assertEquals("blue", $this->user->tags()->first()->color);
    }

    /** @test */
    public function he_can_tag_his_conversation()
    {
        $this->user = $this->makeUser();
        $tag = new Tag([
            'name' => 'default',
            'color' => 'gray'
        ]);
        $this->user->addNewTag($tag);

        // Send Message!
        $receiver = $this->makeUser();
        $data = [
            'content' => 'SomeContent',
            'to_id' => $receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->user
            ->writes($message)
            ->to($receiver)
            ->send();

        $this->assertEquals(1, $this->user->sent()->count());

        $this->assertTrue($message->concerns($this->user)->putTag($tag));

        $this->assertTrue($message->concerns($this->user)->getTag()->name === $tag->name);

        $this->assertNull($message->concerns($receiver)->getTag());
    }

    /** @test */
    public function he_removes_a_tag_from_a_message()
    {
        $this->user = $this->makeUser();
        $tag = new Tag([
            'name' => 'default',
            'color' => 'gray'
        ]);
        $this->user->addNewTag($tag);

        // Send Message!
        $receiver = $this->makeUser();
        $data = [
            'content' => 'SomeContent',
            'to_id' => $receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->user
            ->writes($message)
            ->to($receiver)
            ->send();
        // its affected
        $this->assertTrue($message->concerns($this->user)->putTag($tag));
        // its removed
        $this->assertTrue($message->concerns($this->user)->removeTag());
        // it doesn't exist
        $this->assertNull($message->concerns($this->user)->getTag());
    }

    /** @test */
    public function he_can_change_the_tag_of_message()
    {
        $this->user = $this->makeUser();
        $tag = new Tag([
            'name' => 'default',
            'color' => 'gray'
        ]);

        $tagTwo = new Tag([
            'name' => 'default',
            'color' => 'gray'
        ]);
        $this->user->addNewTag($tag);
        $this->user->addNewTag($tagTwo);

        // Send Message!
        $receiver = $this->makeUser();
        $data = [
            'content' => 'SomeContent',
            'to_id' => $receiver->id
        ];

        list($message, $receiver) = MessageHandler::create($data);

        $this->user
            ->writes($message)
            ->to($receiver)
            ->send();

        $this->assertEquals(1, $this->user->sent()->count());

        $this->assertTrue($message->concerns($this->user)->putTag($tag));

        $this->assertTrue($message->concerns($this->user)->getTag()->name === $tag->name);

        //change the tag
        $this->assertTrue($message->concerns($this->user)->putTag($tagTwo));
        $this->assertTrue($message->concerns($this->user)->getTag()->name === $tagTwo->name);

    }
    /**
     * Make one user
     *
     * @return mixed
     */
    public function makeUser()
    {
        return factory(User::class)->create();
    }

    /**
     * Make a tag
     *
     * @param null $user
     * @return mixed
     */
    public function makeTag($user = null)
    {
        if(is_null($user))
        {
            return factory(Tag::class)->create();
        }
        return factory(Tag::class)->create([
            'user_id' => $user->id
        ]);
    }
}