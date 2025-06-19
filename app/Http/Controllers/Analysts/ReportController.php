<?php

namespace App\Http\Controllers\Analysts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\Purchase;
use App\Models\SupplyChain\Delivery;
use App\Models\SupplyChain\Shipment;
use App\Models\Production\ProductionBatch;

class ReportController extends Controller
{
    public function index()
    {
        $headerTitle = 'Reports Dashboard';
        return view('pages.reports', compact('headerTitle'));
    }
}
