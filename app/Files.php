<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    //
	protected $fillable = [
        'userid', 'filename', 'mime',  'original_filename', 'type', 'download',
    ];
}
