<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // Demo recommendations
        $recommendations = collect([
            (object)[
                'name' => 'Toyota Corolla 2022',
                'description' => 'A reliable and fuel-efficient sedan.'
            ],
            (object)[
                'name' => 'Honda Civic 2023',
                'description' => 'Sporty, modern, and efficient.'
            ],
        ]);

        // Demo retailers and inventory
        $retailers = collect([
            (object)[
                'name' => 'AutoMart',
                'product_inventory' => [
                    ['car_model' => 'Toyota Corolla 2022', 'in_stock' => 4],
                    ['car_model' => 'Honda Civic 2023', 'in_stock' => 2],
                ]
            ],
            (object)[
                'name' => 'City Cars',
                'product_inventory' => [
                    ['car_model' => 'Ford Focus 2021', 'in_stock' => 3],
                ]
            ],
        ]);

        return view('dashboards.customer.index', compact('recommendations', 'retailers'));
    }
} 