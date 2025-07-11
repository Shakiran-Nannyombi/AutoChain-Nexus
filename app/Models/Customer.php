<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Purchase;
use App\Models\Product;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'segment' // Field to store the cluster ID
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Recommend products based on customer segmentation.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recommendProducts()
    {
        // Ensure the customer belongs to a segment
        if (is_null($this->segment)) {
            return collect(); // Return an empty collectioon if no segment is assigned
        }

        // Get IDs of customers in the same segment
        $customerIdsInSegment = Customer::where('segment', $this->segment)->pluck('id');

        // Get popular products purchased by these customers
        $popularProducts = \App\Models\Product::whereHas('purchases', function ($query) use ($customerIdsInSegment) {
            $query->whereIn('customer_id', $customerIdsInSegment);
        })
        ->withCount('purchases') // Count the number of purchases for each product
        ->orderBy('purchases_count', 'desc') // Sort by purchase frequency
        ->take(5) // Limit to top 5 products
        ->get();

        return $popularProducts;
    }
}