<?php

namespace App\Http\Controllers;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Http\Request;
use FFMpeg\Format\Audio\Mp3;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Redirect;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $fileName = Auth::id() . '/' . Auth::id() . '_' . Str::random(64) . '.mp3';
        $file = FFMpeg::open($request->file('files')[0]);

        $audioStream = $file->getAudioStream();

        if (!$audioStream) {
            return Redirect::back()->withErrors([
                'media' => 'The uploaded media does not contain an audio track.',
            ]);
        }

        $file->export()
            ->toDisk('artist_mp3s')
            ->addFilter([
                '-filter:a',
                'loudnorm=I=-14:LRA=11:TP=-1',
            ])
            ->addFilter([
                '-q:a',
                '0',
            ])
            ->inFormat(new Mp3)
            ->save("$fileName");
    }
}
