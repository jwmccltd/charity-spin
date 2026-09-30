<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FileProcessProgressController extends Controller
{
    public function show($trackerKey)
    {
        // Retrieve the progress array from the Redis cache
        $data = Cache::store('redis')->get($trackerKey);

        if (!$data) {
            return response()->json([
                'status' => 'pending',
                'progress' => 0
            ]);
        }

        return response()->json($data);
    }
}
