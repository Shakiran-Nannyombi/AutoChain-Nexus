<?php

namespace App\Http\Controllers\Analysts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Inventory\InventoryItem;
use App\Models\Warehouse\Car;

class AnalyticsController extends Controller
{
    public function index()
    {
        $headerTitle = 'Analytics Dashboard';

        // Dummy data for Revenue Trends Chart
        $revenueTrendsData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            'data' => [65, 59, 80, 81, 56, 55, 40],
        ];

        return view('pages.analytics', compact('headerTitle', 'revenueTrendsData'));
    }
}
