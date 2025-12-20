<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ProcessFlowRepositoryInterface
{
    /**
     * Get all process flows.
     *
     * @return Collection
     */
    public function getAll(): Collection;
}
