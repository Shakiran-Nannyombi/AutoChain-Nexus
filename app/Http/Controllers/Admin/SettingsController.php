<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SettingsUpdateRequest;
use App\Models\Order;
use App\Models\ActivityLog;
use App\Models\WorkOrder;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $headerTitle = 'Settings';
        $user = Auth::user();

        // Calculate real data for Activity Summary
        $ordersProcessed = Order::where('status', 'completed')->count();

        // Assuming 'vendor_meeting' or 'report_generated' actions are logged
        $vendorMeetings = ActivityLog::where('action', 'vendor_meeting')->count();
        $reportsGenerated = ActivityLog::where('action', 'report_generated')->count();

        $totalWorkOrders = WorkOrder::count();
        $completedWorkOrders = WorkOrder::where('status', 'completed')->count();
        $taskCompletion = $totalWorkOrders > 0 ? round(($completedWorkOrders / $totalWorkOrders) * 100) : 0;

        $activitySummary = [
            'ordersProcessed' => $ordersProcessed,
            'vendorMeetings' => $vendorMeetings,
            'reportsGenerated' => $reportsGenerated,
            'taskCompletion' => $taskCompletion,
        ];

        return view('pages.settings', compact('headerTitle', 'user', 'activitySummary'));
    }

    public function update(SettingsUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->fill($request->validated());
        $user->save();

        return back()->with('status', 'settings-updated');
    }
}
