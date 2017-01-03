<?php

namespace Inani\Messager;

use Inani\Messager\Helpers\MessageStatus;
use Inani\Messager\Helpers\QueryMessages;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use MessageStatus, QueryMessages;

    protected $fillable = [
        'from_id', 'to_id', 'content', 'state'
    ];
}
