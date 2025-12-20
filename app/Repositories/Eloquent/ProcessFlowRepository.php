<?php

namespace App\Repositories\Eloquent;

use App\Models\ProcessFlow;
use App\Repositories\Contracts\ProcessFlowRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProcessFlowRepository implements ProcessFlowRepositoryInterface
{
    /**
     * Get all process flows.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return ProcessFlow::all();
    }
}
