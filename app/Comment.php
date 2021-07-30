<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     // fillable 변수
	protected $fillable = [
        'boardid', 'userid', 'content', 'frontcomment', 'anonymous',
    ];
}
