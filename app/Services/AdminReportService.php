<?php

namespace App\Services;

use App\Models\ScheduledReport;
use App\Models\User;
use Illuminate\Http\Request;

class AdminReportService
{
    /**
     * Get all scheduled reports with demo data if empty.
     */
    public function getScheduledReports()
    {
        $scheduledReports = ScheduledReport::all();
        
        if ($scheduledReports->isEmpty()) {
            $scheduledReports = collect([
                (object)[
                    'id' => 1,
                    'report_type' => 'user_activity',
                    'recipients' => 'manager@example.com, ceo@example.com',
                    'frequency' => 'weekly',
                ],
                (object)[
                    'id' => 2,
                    'report_type' => 'inventory_summary',
                    'recipients' => 'ops@example.com',
                    'frequency' => 'monthly',
                ],
                (object)[
                    'id' => 3,
                    'report_type' => 'validation_outcomes',
                    'recipients' => 'compliance@example.com',
                    'frequency' => 'daily',
                ],
            ]);
        }
        
        return $scheduledReports;
    }

    /**
     * Get user roles and users for report configuration.
     */
    public function getUsersAndRoles()
    {
        $userRoles = User::distinct()->pluck('role')->filter()->values();
        $users = User::select('id', 'name', 'email', 'role')->get();
        
        return compact('userRoles', 'users');
    }

    /**
     * Schedule a new report.
     */
    public function scheduleReport(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|string|in:user_activity,inventory_summary,validation_outcomes',
            'recipients' => 'required|string',
            'frequency' => 'required|string|in:daily,weekly,monthly',
        ]);

        return ScheduledReport::create($validated);
    }

    /**
     * Delete a scheduled report.
     */
    public function deleteReport(ScheduledReport $report)
    {
        $report->delete();
    }

    /**
     * Generate report view data.
     */
    public function getReportViewData($reportId)
    {
        return ['reportId' => $reportId];
    }

    /**
     * Generate dummy report file for download.
     */
    public function generateDummyReport($reportId)
    {
        $dummyPath = public_path('dummy-report.pdf');
        
        if (!file_exists($dummyPath)) {
            file_put_contents($dummyPath, 'This is a dummy PDF report for report ID: ' . $reportId);
        }
        
        return $dummyPath;
    }
}
