<?php
namespace App\Http\Controllers\Manufacturer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DemandPrediction
{
    public function getDemandForecast(Request $request)
    {
        $response = Http::post('http://127.0.0.1:8001/api/predict', [
            'region' => $request->input('region'),
            'model' => $request->input('model'),
        ]);

        return response()->json($response->json());
    }
}
