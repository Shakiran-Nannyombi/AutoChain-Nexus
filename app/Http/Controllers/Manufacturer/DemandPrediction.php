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

            // Call FastAPI service on port 8001 with timeout
            $response = Http::timeout(60)->post('http://127.0.0.1:8001/forecast', [
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
