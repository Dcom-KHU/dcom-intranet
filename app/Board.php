<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    // fillable 변수
	protected $fillable = [
        'id', 'boardid', 'userid', 'title', 'content', 'anonymous', 'viewer',
    ];
}
