<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\VendorOrder;

interface VendorOrderRepositoryInterface
{
    /**
     * Get orders by vendor ID.
     *
     * @param int $vendorId
     * @return Collection
     */
    public function getByVendorId(int $vendorId): Collection;

    /**
     * Create a new order.
     *
     * @param array $data
     * @return VendorOrder
     */
    public function create(array $data): VendorOrder;

    /**
     * Find an order by ID.
     *
     * @param int $id
     * @return VendorOrder|null
     */
    public function find(int $id): ?VendorOrder;

    /**
     * Find an order by ID belonging to a specific vendor.
     *
     * @param int $id
     * @param int $vendorId
     * @return VendorOrder|null
     */
    public function findForVendor(int $id, int $vendorId): ?VendorOrder;
}
