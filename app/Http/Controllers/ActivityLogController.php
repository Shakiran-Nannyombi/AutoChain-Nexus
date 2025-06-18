<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = ActivityLog::with(['user', 'targetUser'])
            ->latest()
            ->paginate(20); // Paginate for better performance

        return view('pages.activity-logs', compact('activities'));
    }
}
