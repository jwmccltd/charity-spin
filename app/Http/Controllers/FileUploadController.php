<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Jobs\ProcessCampaignAudio;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $file = $request->file('files')[0];
        $fileName = Auth::id() . '/' . Auth::id() . '_' . $request->input('unique_key');
        $path = $file->storeAs('/', $fileName . '.' . $file->getClientOriginalExtension(), 'artist_mp3s');

        ProcessCampaignAudio::dispatchSync($fileName, $file->getClientOriginalExtension(), $request->input('unique_key'));
    }
}
