<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = ActivityLog::with(['user', 'targetUser'])
            ->latest()
            ->paginate(20); // Paginate for better performance

        $headerTitle = 'Activity Log'; // Define the header title

        return view('pages.activity-logs', compact('activities', 'headerTitle'));
    }
}
