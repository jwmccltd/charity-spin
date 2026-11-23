<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\MusicGenre;
use Storage;

class CampaignController extends Controller
{
    //
    public function create()
    {
        return inertia('campaigns/Create', [
            'musicGenres' => MusicGenre::all(),
        ]);
    }

    public function getCampaignByTrackerKey($trackerKey)
    {
        $campaign = Campaign::where('file_key', $trackerKey)->first();

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        return response()->json($campaign);
    }

    public function getCampaignById($id)
    {
        $campaign = Campaign::where('id', $id)->first();

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        return response()->json($campaign);
    }

    public function getCampaignTrack($id) {
        $campaign = Campaign::where('id', $id)->first();

        // 1. Define the relative path within your private disk
        $path = $campaign->audio_file_path; 

        // 2. Check if the file actually exists
        if (!Storage::disk('artist_mp3s')->exists($path)) {
            abort(404, 'Audio file not found.');
        }

        // 3. Get the absolute server path to the file
        $absolutePath = Storage::disk('artist_mp3s')->path($path);

        // 4. Serve the file inline with the correct Content-Type header
        return response()->file($absolutePath, [
            'Content-Type' => 'audio/mpeg',
            'Content-Disposition' => 'inline; filename="' . $campaign->original_audio_file_name . '"',
        ]);
    }

    public function getCampaignArtwork($id)
    {
        $campaign = Campaign::where('id', $id)->firstOrFail();

        // Get the relative path to the image
        $path = $campaign->artwork_file_path;

        // Check if the image exists
        if (!Storage::disk('artist_artwork')->exists($path)) {
            abort(404, 'Image file not found.');
        }

        // Get the absolute server path
        $absolutePath = Storage::disk('artist_artwork')->path($path);

        // Serve the image inline in the browser
        return response()->file($absolutePath, [
            'Content-Type' => 'image/jpeg',
            'Content-Disposition' => 'inline; filename="' .
                basename($campaign->original_artwork_file_name) . '"',
        ]);
    }
}
