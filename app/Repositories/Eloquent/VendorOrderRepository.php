<?php

namespace App\Repositories\Eloquent;

use App\Models\VendorOrder;
use App\Repositories\Contracts\VendorOrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class VendorOrderRepository implements VendorOrderRepositoryInterface
{
    /**
     * Get orders by vendor ID.
     *
     * @param int $vendorId
     * @return Collection
     */
    public function getByVendorId(int $vendorId): Collection
    {
        return VendorOrder::where('vendor_id', $vendorId)
            ->with('manufacturer')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @return VendorOrder
     */
    public function create(array $data): VendorOrder
    {
        return VendorOrder::create($data);
    }

    /**
     * Find an order by ID.
     *
     * @param int $id
     * @return VendorOrder|null
     */
    public function find(int $id): ?VendorOrder
    {
        return VendorOrder::find($id);
    }

    /**
     * Find an order by ID belonging to a specific vendor.
     *
     * @param int $id
     * @param int $vendorId
     * @return VendorOrder|null
     */
    public function findForVendor(int $id, int $vendorId): ?VendorOrder
    {
        return VendorOrder::where('vendor_id', $vendorId)->where('id', $id)->first();
    }
}
