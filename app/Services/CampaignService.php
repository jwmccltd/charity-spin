<?php

namespace App\Services;
use App\Models\Campaign;

class CampaignService
{
    public function __construct() {}

    public function createUpdate(array $data, $campaignId = null)
    {
        // Create or update the campaign record in the database.
        if ($campaignId) {
            return Campaign::where('id', $campaignId)->update($data);
        }
        return Campaign::create($data);
    }
}