<?php

namespace App\Services;

use App\Repositories\Contracts\VendorOrderRepositoryInterface;
use App\Models\VendorActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VendorNotification;
use App\Notifications\VendorOrderCreated;
use Carbon\Carbon;

class VendorOrderService
{
    protected VendorOrderRepositoryInterface $repository;

    public function __construct(VendorOrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new order with side effects (logs, notifications).
     *
     * @param array $data
     * @param User|null $manufacturer
     * @return mixed
     */
    public function createOrder(array $data, ?User $manufacturer)
    {
        $orderData = [
            'manufacturer_id' => $data['manufacturer_id'],
            'vendor_id' => Auth::id(),
            'product' => $data['product_id'],
            'product_name' => $data['product_name'],
            'product_category' => $data['product_category'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'total_amount' => $data['total_amount'],
            'status' => 'pending',
            'ordered_at' => Carbon::now(),
            'expected_delivery_date' => $data['expected_delivery_date'] ?? null,
            'delivery_point' => $data['delivery_point'] ?? null,
            'notes' => $data['notes'],
        ];

        $order = $this->repository->create($orderData);

        // Log activity
        VendorActivity::create([
            'vendor_id' => Auth::id(),
            'activity' => 'Created new order',
            'details' => 'Order ID: ' . $order->id . ', Product: ' . $orderData['product_name'] . ', Quantity: ' . $order->quantity,
        ]);

        // Notify vendor
        Auth::user()->notify(new VendorNotification('Order Created', 'Your order #' . $order->id . ' has been created.'));

        // Notify manufacturer
        if ($manufacturer) {
            $manufacturer->notify(new VendorOrderCreated($order));
        }

        return $order;
    }

    /**
     * Get dashboard data for the vendor.
     *
     * @param int $vendorId
     * @return array
     */
    public function getDashboardData(int $vendorId): array
    {
        $manufacturerOrders = $this->repository->getByVendorId($vendorId);
        
        // Retailer orders logic could also be moved here or to a RetailerOrderService
        // For now, we will return manufacturerOrders and let the controller handle the rest or move gradually.
        
        return compact('manufacturerOrders');
    }
}
