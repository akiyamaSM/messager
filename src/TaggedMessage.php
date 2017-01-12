<?php

namespace Inani\Messager;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TaggedMessage extends Model
{
    protected $table = 'tagged_messages';

    protected $fillable =[
        'id', 'message_id', 'tag_from_id', 'tag_to_id'
    ];

    /**
     * Get the sender TAG
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tagSender()
    {
        return $this->belongsTo(Tag::class, 'tag_from_id');
    }

    /**
     * Get the sender TAG
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tagReceiver()
    {
        return $this->belongsTo(Tag::class, 'tag_to_id');
    }

    /**
     * Save Tag
     *
     * @param Message $message
     * @param User $user
     * @param Tag $tag
     * @return bool
     */
    public function saveTag(Message $message, User $user, Tag $tag)
    {
        if($message->isSentBy($user))
        {
            $this->tag_from_id = $tag->id;
            return $this->save();
        }

        $this->tag_to_id = $tag->id;

        return $this->save();
    }

    /**
     * Get the tag of the message concerning the user
     *
     * @param Message $message
     * @param User $user
     * @return Tag|null
     */
    public static function getTagByMessageAndUser(Message $message, User $user)
    {
        $tagged = static::where([
            'message_id' => $message->getKey()
        ])->firstOrFail();

        if($message->isSentBy($user))
        {
            return $tagged->tagSender;
        }
        return $tagged->tagReceiver;
    }
}
