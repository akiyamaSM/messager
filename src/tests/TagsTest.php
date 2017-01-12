<?php

namespace Inani\Messager\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Http\Request;
use Inani\Messager\Tag;
use Mockery\CountValidator\Exception;

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