<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\ProcessCampaignAudio;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('files')[0];
        $fileName = Auth::id() . '/' . Auth::id() . '_' . $request->input('unique_key');
        $path = $file->storeAs('/', $fileName . '.' . $file->getClientOriginalExtension(), 'artist_mp3s');

        ProcessCampaignAudio::dispatch($path, $request->input('unique_key'));
    }
}
