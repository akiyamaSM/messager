<?php
namespace Inani\Messager\Helpers;

use App\Http\Requests\Request;
use Inani\Messager\Tag;
use InvalidArgumentException;

trait TagsCreator
{
   /*
    * @var Tag
    */
    protected $tag;

    /**
     * A user has many tags
     *
     * @return mixed
     */
    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Attach a new Tag to the user
     *
     * @param $data
     * @return Tag
     * @throws \InvalidArgumentException
     */
    public function addNewTag($data)
    {
        if($data instanceof Tag)
        {
            $this->tags()->save($data);
            return $data;
        }

        if(is_array($data))
        {
            return Tag::create([
                'color' => $data['color'],
                'name' => $data['name'],
                'user_id' => $this->getKey()
            ]);
        }

        if($data instanceof Request)
        {
            return Tag::create($data->all());
        }
        throw new InvalidArgumentException();
    }

    /**
     * Check if the tag belongs to him
     *
     * @param Tag $tag
     * @return bool
     */
    public function hasTag(Tag $tag)
    {
        return $this->getKey() == $tag->user_id;
    }

    /**
     * Assign the Tag
     *
     * @param Tag $tag
     * @return $this
     * @throws InvalidArgumentException
     */
    public function tag(Tag $tag)
    {
        if($this->hasTag($tag))
        {
            $this->tag = $tag;
            return $this;
        }
        throw new InvalidArgumentException("This tag doesn't belong to this user");
    }

    /**
     * Assign the new name
     *
     * @param $name
     * @return $this
     * @throws \Exception
     */
    public function name($name)
    {
        if(! is_null($this->tag)){
            $this->tag->name = $name;
            return $this;
        }
        throw new \Exception("The tag is not set yet.");
    }

    /**
     * Assign the new name
     *
     * @param $color
     * @return $this
     * @throws \Exception
     */
    public function color($color)
    {
        if(! is_null($this->tag)){
            $this->tag->color = $color;
            return $this;
        }
        throw new \Exception("The tag is not set yet.");
    }

    /**
     * Apply the modifications
     *
     * @return mixed
     * @throws \Exception
     */
    public function apply()
    {
        if(! is_null($this->tag)){
            return $this->tag->save();
        }
        throw new \Exception("The tag is not set yet.");
    }
}