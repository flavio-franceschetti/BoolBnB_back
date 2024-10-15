<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TomTomService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tomtom.key');
        $this->baseUrl = config('services.tomtom.base_url');
    }

    public function getCoordinates($address)
    {
        $response = Http::get("{$this->baseUrl}search/2/geocode/{$address}.json", [
            'key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
