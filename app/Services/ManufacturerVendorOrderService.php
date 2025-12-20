<?php

namespace App\Services;

use App\Models\VendorOrder;
use App\Models\User;
use App\Models\Product;
use App\Notifications\VendorNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ManufacturerVendorOrderService
{
    /**
     * Get vendor orders for the manufacturer.
     */
    public function getVendorOrders($manufacturerId)
    {
        return VendorOrder::where('manufacturer_id', $manufacturerId)
            ->with(['vendor', 'manufacturer'])
            ->orderByRaw("FIELD(status, 'pending') DESC")
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get a specific vendor order.
     */
    public function getOrder($orderId, $manufacturerId)
    {
        return VendorOrder::where('manufacturer_id', $manufacturerId)
            ->with(['vendor', 'manufacturer'])
            ->findOrFail($orderId);
    }

    /**
     * Accept an order and update stock.
     */
    public function acceptOrder($orderId, $manufacturerId, array $data)
    {
        $order = $this->getOrder($orderId, $manufacturerId);
        
        // Stock check
        $product = Product::find($order->product);
        if ($product) {
            if ($product->stock < $order->quantity) {
                return ['success' => false, 'message' => 'Insufficient stock.', 'order' => $order];
            }
            $product->decrement('stock', $order->quantity);
            
            if ($product->stock < 5) {
                Log::warning('Stock for product ' . $product->name . ' is low: ' . $product->stock);
            }
        }

        $order->update([
            'status' => 'accepted',
            'accepted_at' => Carbon::now(),
            'notes' => $data['notes'] ?? $order->notes,
            'expected_delivery_date' => $data['expected_delivery_date'] ?? $order->expected_delivery_date,
            'unit_price' => $data['unit_price'] ?? $order->unit_price,
            'total_amount' => ($data['unit_price'] ?? $order->unit_price) * $order->quantity,
        ]);

        // Notify vendor
        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $vendor->notify(new VendorNotification(
                'Order Accepted', 
                'Your order #' . $order->id . ' has been accepted by the manufacturer.'
            ));
        }

        return ['success' => true, 'message' => 'Order accepted successfully!', 'order' => $order];
    }

    /**
     * Reject an order.
     */
    public function rejectOrder($orderId, $manufacturerId, $reason)
    {
        $order = $this->getOrder($orderId, $manufacturerId);
        
        if ($order->status !== 'pending') {
            return ['success' => false, 'message' => 'Order is not pending.'];
        }

        $order->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 'Rejected: ' . $reason,
        ]);

        // Notify vendor
        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $this->sendRejectionEmail($vendor, $order, $reason);
            $vendor->notify(new VendorNotification(
                'Order Rejected',
                'Your order #' . $order->id . ' has been rejected. Reason: ' . $reason
            ));
        }

        return ['success' => true, 'message' => 'Order rejected and vendor notified.'];
    }

    /**
     * Confirm order with additional details and invoice.
     */
    public function confirmOrder($orderId, $manufacturerId, array $data)
    {
        $order = $this->getOrder($orderId, $manufacturerId);

        if ($order->status !== 'pending') {
            return ['success' => false, 'message' => 'Order is not pending.'];
        }

        $product = $this->findProductForOrder($order);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Product not found.'];
        }
        if ($product->stock < $order->quantity) {
            return ['success' => false, 'message' => 'Insufficient stock to confirm this order.'];
        }

        $order->update([
            'status' => 'accepted',
            'accepted_at' => now(),
            'notes' => ($order->notes ? $order->notes . "\n" : '') .
                'Delivery Date: ' . $data['delivery_date'] . ', Address: ' . $data['delivery_address'] . ', Driver: ' . $data['driver_name'],
        ]);
        $product->decrement('stock', $order->quantity);

        $this->sendInvoiceEmail($order, $product, $data);

        $vendor = User::find($order->vendor_id);
        if ($vendor) {
            $vendor->notify(new VendorNotification(
                'Order Accepted',
                'Your order #' . $order->id . ' has been accepted by the manufacturer. An invoice has been sent to your email.'
            ));
        }

        return ['success' => true, 'message' => 'Order confirmed, stock updated, and invoice sent.', 'order' => $order->fresh()];
    }

    /**
     * Update order details/invoice.
     */
    public function updateInvoice($orderId, $manufacturerId, array $data)
    {
        $order = $this->getOrder($orderId, $manufacturerId);
        
        $order->update([
            'product_name' => $data['product_name'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'expected_delivery_date' => $data['expected_delivery_date'],
            'notes' => $data['notes'],
            'total_amount' => $data['unit_price'] * $data['quantity'],
        ]);

        return ['success' => true, 'order' => $order];
    }

    public function resendInvoice($orderId, $manufacturerId)
    {
        $order = $this->getOrder($orderId, $manufacturerId);
        $product = Product::find($order->product);
        
        // Use dummy delivery details if missing
        $data = [
            'delivery_date' => $order->expected_delivery_date,
            'delivery_address' => 'N/A',
            'driver_name' => 'N/A' 
        ];
        
        $this->sendInvoiceEmail($order, $product, $data);
        return ['success' => true];
    }

    private function findProductForOrder($order)
    {
        $product = Product::where('name', $order->product_name ?? $order->product)
            ->where(function($q) use ($order) {
                if ($order->manufacturer_id) $q->where('manufacturer_id', $order->manufacturer_id);
                if ($order->vendor_id) $q->orWhere('vendor_id', $order->vendor_id);
            })
            ->first();

        if (!$product) {
            $product = Product::where('name', $order->product_name ?? $order->product)->first();
        }
        
        return $product;
    }

    private function sendRejectionEmail($vendor, $order, $reason)
    {
        $body = '<h2>Your order #' . $order->id . ' has been rejected.</h2>' .
                '<p><b>Reason:</b> ' . e($reason) . '</p>';
        $apiUrl = env('JAVA_MAIL_BASE_URL');
        if (!$apiUrl) {
            Log::warning('Email API URL not set. Rejection email not sent.');
            return;
        }

        try {
            Http::post($apiUrl . '/api/v1/send-email', [
                'to' => $vendor->email,
                'subject' => 'Order #' . $order->id . ' Rejected',
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending rejection email: ' . $e->getMessage());
        }
    }

    private function sendInvoiceEmail($order, $product, $data)
    {
        $invoiceHtml = view('emails.reports.invoice', [
            'order' => $order,
            'product' => $product,
            'manufacturer' => Auth::user(),
            'vendor' => $order->vendor,
            'delivery_date' => $data['delivery_date'] ?? null,
            'delivery_address' => $data['delivery_address'] ?? null,
            'driver_name' => $data['driver_name'] ?? null,
        ])->render();

        $apiUrl = env('JAVA_MAIL_BASE_URL');
        if (!$apiUrl) {
            Log::warning('Email API URL not set. Invoice email not sent.');
            return;
        }

        try {
            Http::post($apiUrl . '/api/v1/send-email', [
                'to' => $order->vendor->email,
                'subject' => 'Order Invoice #' . $order->id,
                'body' => $invoiceHtml,
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending invoice email: ' . $e->getMessage());
        }
    }
}
