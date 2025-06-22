<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analyst;
use App\Models\Manufacturer;
use App\Models\Retailer;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\BackupCollection;
use Spatie\Backup\Helpers\Format;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->count();
        $totalUsers = User::count();

        $roleCounts = [
            'Suppliers' => Supplier::count(),
            'Manufacturers' => Manufacturer::count(),
            'Retailers' => Retailer::count(),
            'Vendors' => Vendor::count(),
            'Analysts' => Analyst::count(),
        ];

        $activeUsers = User::where('status', 'approved')->where('role', '!=', 'admin')->count();

        return view('dashboards.admin.index', [
            'pendingUsers' => $pendingUsers,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'roleCounts' => $roleCounts,
        ]);
    }

    public function systemFlow()
    {
        return view('dashboards.admin.system-flow');
    }

    public function analytics()
    {
        return view('dashboards.admin.analytics');
    }

    public function reports()
    {
        return view('dashboards.admin.reports');
    }

    public function settings()
    {
        return view('dashboards.admin.settings');
    }

    public function backups()
    {
        $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
        $files = $disk->allFiles(config('backup.backup.name'));
    
        $backups = collect($files)
            ->map(function ($file) use ($disk) {
                return [
                    'path' => $file,
                    'date' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                    'size' => Format::humanReadableSize($disk->size($file)),
                ];
            })
            ->sortByDesc('date')
            ->values();
    
        return view('dashboards.admin.backups', ['backups' => $backups]);
    }

    public function createBackup()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            return redirect()->back()->with('success', 'New backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup creation failed: ' . $e->getMessage());
        }
    }
}
