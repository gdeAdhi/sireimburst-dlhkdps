<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ORSController extends Controller
{
    public function getRoute(Request $request)
    {
        $response = Http::withHeaders([
            'Authorization' => env('ORS_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car', [
            'coordinates' => $request->input('coordinates')
        ]);
        \Log::info($response->json());
        return response()->json($response->json());
    }
}

