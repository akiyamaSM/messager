<?php

namespace Inani\Messager\Helpers;

use App\User;
use Inani\Messager\Tag;
use Inani\Messager\TaggedMessage;
use InvalidArgumentException;

trait TaggableMessage {

    protected $user;

    /**
     * Set the user concerned
     *
     * @param $user
     * @return $this
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function concerns($user)
    {
        if($user instanceof User)
        {
            $this->user = $user;
            return $this;
        }

        if(is_int($user)){
            $this->user = User::findOrfail($user);
            return $this;
        }
    }

    /**
     * Assign the tag to the message
     *
     * @param $tag
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function putTag($tag)
    {
        if( is_int($tag))
        {
            $tag = Tag::findOrFail($tag);
        }

        if($tag instanceof Tag)
        {
            if($this->user->hasTag($tag)){

                $taggedMessage = TaggedMessage::firstOrNew([
                    'message_id' => $this->getKey()
                ]);

            }else{
                throw new InvalidArgumentException("This tag doesn't belong to this user");
            }
        }
        return $taggedMessage->saveTag($this, $this->user, $tag);
    }

    /**
     * Get a tag
     *
     * @return Tag|null
     */
    public function getTag()
    {
        if(!is_null($this->user)){
            /** @var \Inani\Messager\Message $this */
            return TaggedMessage::getTagByMessageAndUser($this, $this->user);
        }
    }

    /**
     * Remove a tag
     *
     * @return mixed
     */
    public function removeTag()
    {
        $taggedMessage = TaggedMessage::where([
            'message_id' => $this->getKey()
        ])->firstOrFail();

        return $taggedMessage->removeTag($this, $this->user);
    }
}