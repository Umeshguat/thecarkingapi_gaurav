<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = 'AIzaSyB-x-33XnTu8z_687iOePJjn8WWitnu_ak';
    }

    public function getGoogleReview(Request $request)
    {
        try {
            $query = $request->query('query');
            if (!$query) return response()->json(['error' => 'query param required'], 400);

            $url = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json';
            $res = Http::get($url, [
                'input' => $query,
                'inputtype' => 'textquery',
                'fields' => 'place_id,name,formatted_address',
                'key' => $this->apiKey,
            ]);

            if ($res->failed()) abort(500, 'Places find API failed');

            $placeId = $res['candidates'][0]['place_id'];

            // cache to avoid repeated calls
            $cacheKey = "google_place_details_{$placeId}";
            $details = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($placeId) {
                $url = 'https://maps.googleapis.com/maps/api/place/details/json';

                $response = Http::get($url, [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,reviews,user_ratings_total,formatted_address,website',
                    'key' => $this->apiKey,
                ]);

                if ($response->failed()) {
                    abort(500, 'Places API request failed');
                }

                $json = $response->json();
                if (data_get($json, 'status') !== 'OK') {
                    // return error info to client
                    return ['error' => data_get($json, 'status'), 'message' => data_get($json, 'error_message')];
                }

                return $json['result'] ?? [];
            });

            return response()->json([
                'success' => 200,
                'message' => 'Google review get successfully.',
                'data' => $details
            ], 200);
        } catch (\Throwable $th) {
            
            return response()->json([
                'success' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
