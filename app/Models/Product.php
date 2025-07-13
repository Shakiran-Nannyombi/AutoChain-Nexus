<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Customer;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
}
