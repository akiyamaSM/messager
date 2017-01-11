<?php

namespace Inani\Messager;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name', 'color', 'user_id'
    ];
}
