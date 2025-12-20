<?php

/**
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledReport;
use Illuminate\Http\Request;
use App\Services\AdminDashboardService;
use App\Services\AdminReportService;
use App\Services\AdminSystemService;
use App\Services\AdminChatService;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $reportService;
    protected $systemService;
    protected $chatService;

    public function __construct(
        AdminDashboardService $dashboardService,
        AdminReportService $reportService,
        AdminSystemService $systemService,
        AdminChatService $chatService
    ) {
        $this->dashboardService = $dashboardService;
        $this->reportService = $reportService;
        $this->systemService = $systemService;
        $this->chatService = $chatService;
    }

    /**
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany notifications()
     */
    public function index()
    {
        $stats = $this->dashboardService->getDashboardStats();
        return view('dashboards.admin.index', $stats);
    }

    public function systemFlow()
    {
        $data = $this->dashboardService->getSystemFlowData();
        return view('dashboards.admin.system-flow', $data);
    }

    public function analytics()
    {
        $data = $this->dashboardService->getAnalyticsData();
        return view('dashboards.admin.analytics', $data);
    }

    public function reports()
    {
        $scheduledReports = $this->reportService->getScheduledReports();
        $usersAndRoles = $this->reportService->getUsersAndRoles();
        
        return view('dashboards.admin.reports', array_merge(
            compact('scheduledReports'),
            $usersAndRoles
        ));
    }

    public function scheduleReport(Request $request)
    {
        $this->reportService->scheduleReport($request);
        return redirect()->route('admin.reports')->with('success', 'Report scheduled successfully!');
    }

    public function destroyReport(ScheduledReport $report)
    {
        try {
            $this->reportService->deleteReport($report);
            $scheduledReports = $this->reportService->getScheduledReports();
            $usersAndRoles = $this->reportService->getUsersAndRoles();
            
            return view('dashboards.admin.reports', array_merge(
                compact('scheduledReports'),
                $usersAndRoles
            ))->with('warning', 'Scheduled report deleted. (Demo or real)');
        } catch (\Exception $e) {
            $scheduledReports = $this->reportService->getScheduledReports();
            $usersAndRoles = $this->reportService->getUsersAndRoles();
            
            return view('dashboards.admin.reports', array_merge(
                compact('scheduledReports'),
                $usersAndRoles
            ))->with('warning', 'Scheduled report does not exist or is demo data. Cannot delete.');
        }
    }

    public function inventoryOverview()
    {
        return view('dashboards.admin.inventory-overview');
    }

    public function settings()
    {
        $settings = $this->systemService->getSettings();
        return view('dashboards.admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $this->systemService->updateSettings($request);
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully!');
    }

    public function backups()
    {
        $backups = $this->systemService->getBackups();
        return view('dashboards.admin.backups', ['backups' => $backups]);
    }

    public function createBackup()
    {
        try {
            $this->systemService->createBackup();
            return redirect()->back()->with('success', 'New backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup creation failed: ' . $e->getMessage());
        }
    }

    public function chat(Request $request)
    {
        $data = $this->chatService->getChatViewData($request);
        return view('dashboards.admin.chat', $data);
    }

    public function getChatMessages($userId)
    {
        $result = $this->chatService->getMessages($userId);
        
        if (isset($result['code'])) {
            return response()->json([
                'status' => $result['status'],
                'message' => $result['message']
            ], $result['code']);
        }
        
        return response()->json($result);
    }

    public function sendChatMessage(Request $request)
    {
        $result = $this->chatService->sendMessage($request);
        
        if (isset($result['code'])) {
            return response()->json([
                'status' => $result['status'],
                'message' => $result['message']
            ], $result['code']);
        }
        
        return response()->json($result);
    }

    public function viewReport($report)
    {
        $data = $this->reportService->getReportViewData($report);
        return response()->view('dashboards.admin.report-view', $data);
    }

    public function downloadReport($report)
    {
        $dummyPath = $this->reportService->generateDummyReport($report);
        return response()->download($dummyPath, 'report-' . $report . '.pdf');
    }
}
