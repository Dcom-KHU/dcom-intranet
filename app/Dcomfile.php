<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dcomfile extends Model
{
    //
	protected $fillable = [
        'filename', 'realname', 'original_filename',  'download',
    ];
}
