<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InstagramService;
use Illuminate\Http\JsonResponse;

class InstagramController extends Controller
{
    protected $instagramService;

    public function __construct(InstagramService $instagramService)
    {
        $this->instagramService = $instagramService;
    }

    public function getFeed(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $feed = $this->instagramService->getLatestPosts($limit);
            
            return response()->json([
                'success' => true,
                'data' => $feed,
                'message' => 'Instagram feed retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Instagram feed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function thecarkingFeed(Request $request): JsonResponse
    {
        try {
           
            $limit = $request->get('limit', 10);
            $feed = $this->instagramService->tckgetLatestPosts($limit);
            
            return response()->json([
                'success' => true,
                'data' => $feed,
                'message' => 'Instagram feed retrieved successfully'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Instagram feed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
  
}