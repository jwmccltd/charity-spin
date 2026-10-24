<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Jobs\ProcessCampaignAudio;
use App\Jobs\ProcessCampaignArtwork;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        switch ($request->input('upload_type')) {
            case 'artwork':
                $file = $request->file('files')[0];
                $fileName = Auth::id() . '/' . Auth::id() . '_' . $request->input('unique_key');
                $path = $file->storeAs('/', $fileName . '.' . $file->getClientOriginalExtension(), 'artist_artwork');

                ProcessCampaignArtwork::dispatchSync(
                    $fileName,
                    pathinfo($file->getClientOriginalName() . '.mp3', PATHINFO_FILENAME), 
                    $file->getClientOriginalExtension(),
                    $request->input('unique_key'),
                    $request->input('id') ?? null,
                );
                break;
            case 'audio':
                $file = $request->file('files')[0];
                $fileName = Auth::id() . '/' . Auth::id() . '_' . $request->input('unique_key');
                $path = $file->storeAs('/', $fileName . '.' . $file->getClientOriginalExtension(), 'artist_mp3s');

                ProcessCampaignAudio::dispatchSync(
                    $fileName, 
                    pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.mp3', 
                    $file->getClientOriginalExtension(), 
                    $request->input('unique_key'),
                    $request->input('id') ?? null,
                );
                break;
        }
    
    }
}
