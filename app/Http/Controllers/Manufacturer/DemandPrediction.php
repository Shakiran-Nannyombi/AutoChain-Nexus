<?php
namespace App\Http\Controllers\Manufacturer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class DemandPrediction extends Controller
{
    public function getDemandForecast(Request $request)
    {
        try {
            $models = $request->input('models', []);
            if (empty($models)) {
                return response()->json(['error' => 'No car models provided.'], 400);
            }

            // Check for configured FastAPI URL
            $fastApiUrl = env('FASTAPI_URL');
            
            if (!$fastApiUrl) {
                 // Return mock data if service is not configured (Demo Mode)
                 $mockForecast = [];
                 foreach ($models as $model) {
                     $mockForecast[$model] = [
                        'forecast' => rand(50, 500),
                        'confidence' => rand(85, 99) . '%'
                     ];
                 }
                 return response()->json(['status' => 'success', 'data' => $mockForecast, 'mock' => true]);
            }

            // Call FastAPI service with timeout
            $response = Http::timeout(60)->post($fastApiUrl . '/forecast', [
                'models' => $models
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([
                    'error' => 'Failed to get forecast from ML service',
                    'details' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Forecast generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableModelsAndRegions()
    {
        try {
            $models = DB::table('vendor_orders')
                ->where('status', 'fulfilled')
                ->whereNotNull('product')
                ->distinct()
                ->pluck('product')
                ->filter()
                ->values();

            $regions = DB::table('vendor_orders')
                ->where('status', 'fulfilled')
                ->whereNotNull('manufacturer_id')
                ->distinct()
                ->pluck('manufacturer_id')
                ->filter()
                ->values();

            return response()->json([
                'status' => 'success',
                'models' => $models,
                'regions' => $regions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch data: ' . $e->getMessage(),
                'models' => [],
                'regions' => []
            ], 500);
        }
    }
}
