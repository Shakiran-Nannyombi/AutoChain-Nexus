<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalystReport extends Model
{
    protected $fillable = ['title', 'type', 'target_role', 'summary', 'report_file'];
}
