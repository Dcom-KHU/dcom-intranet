<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	// fillable 변수
	protected $fillable = [
        'boardid','userid', 'type', 'title', 'description', 'participation', 'git',
    ];
}
