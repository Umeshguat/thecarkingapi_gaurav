<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class InstagramService
{
    protected $accessToken;
    protected $apiBaseUrl = 'https://graph.instagram.com';
    protected $userId;
    protected $tckaccessToken;
    protected $tckuserId;

    public function __construct()
    {
        $this->accessToken = 'IGAAWhC9TzFAJBZAFNPZADAzT2NhY3dYY1lPV2poUEZAidEo5cTYxcnYteTFfNHZAZAeTRrd194T1UwUVQzQ1N1eDdFQVFweGhVczBrXzFDSmdIUENPZAzNIZA3N1a1d0NDFMMFdyUTlRMFMxVUZA4ejFqcVRRRWY5UzZAGM2lZAVzhhUS0zVQZDZD';
        $this->userId = '17841403442887367';
        
    
        $this->tckaccessToken = 'IGAAQu41oabqhBZAGFwbzE4ODd3Rm16Skxhdm9BamlKNzJFaE5CTHVNR1NOdENSb1U5ek9qanNPb0labUVOdXVobUF4TGtYRVV6SS1jdjRhdEpZATDVqel9HTVdIZAGE3b05TS1VTeWd5UzFiaU1ZAbmdObXl5c1hfdHF0U2Vub0RiZAwZDZD';
        $this->tckuserId = '17841417976979342';
    }

    public function getLatestPosts($limit = 10)
    {
        return $this->fetchAndCacheFeed($this->userId, $this->accessToken, $limit);
    }

    public function tckgetLatestPosts($limit = 10)
    {
        return $this->fetchAndCacheFeed($this->tckuserId, $this->tckaccessToken, $limit);
    }

    protected function fetchAndCacheFeed($userId, $accessToken, $limit = 10)
    {
        // include userId in cache key so different accounts do not collide
        $cacheKey = "instagram_feed_{$userId}_{$limit}";

        return Cache::remember($cacheKey, 3600, function () use ($userId, $accessToken, $limit) {
            $fields = 'id,caption,media_type,media_url,thumbnail_url,permalink,timestamp,username';

            $response = Http::timeout(10)->get("{$this->apiBaseUrl}/{$userId}/media", [
                'fields' => $fields,
                'limit' => $limit,
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatPosts($data['data'] ?? []);
            }

            // provide response body & status for debugging
            $body = $response->body();
            $status = $response->status();
            throw new \Exception("Failed to fetch Instagram feed (status: {$status}): {$body}");
        });
    }
    
    
    protected function formatPosts($posts)
    {
        return array_map(function ($post) {
            return [
                'id' => $post['id'] ?? null,
                'caption' => $post['caption'] ?? '',
                'media_type' => $post['media_type'] ?? '',
                'media_url' => $post['media_url'] ?? '',
                'thumbnail_url' => $post['thumbnail_url'] ?? null,
                'permalink' => $post['permalink'] ?? '',
                'timestamp' => $post['timestamp'] ?? '',
                'username' => $post['username'] ?? '',
                'formatted_date' => isset($post['timestamp']) 
                    ? date('Y-m-d H:i:s', strtotime($post['timestamp'])) 
                    : null
            ];
        }, $posts);
    }

    public function refreshAccessToken($refreshToken = null)
    {
        $refreshToken = $refreshToken ?: config('services.instagram.refresh_token');
        
        $response = Http::get("{$this->apiBaseUrl}/refresh_access_token", [
            'grant_type' => 'ig_refresh_token',
            'access_token' => $refreshToken
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['access_token'] ?? null;
        }

        throw new \Exception('Failed to refresh access token');
    }
}