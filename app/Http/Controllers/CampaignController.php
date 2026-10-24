<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    //
    public function create()
    {
        return inertia('campaigns/Create');
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
}
