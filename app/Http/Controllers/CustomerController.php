<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retailer;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $retailers = Retailer::all();
        return view('dashboards.customer.index', compact('retailers'));
    }
}

