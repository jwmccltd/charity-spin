<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'file_key',
        'audio_file_path',
        'original_audio_file_name',
        'artwork_file_path',
        'original_artwork_file_name',
        'description',
    ];
}
