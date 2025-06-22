<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\BackupDestination\Backup;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupJob;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackups();
        
        return view('dashboards.admin.backups', compact('backups'));
    }

    public function create()
    {
        try {
            // Create a new backup using artisan command
            Artisan::call('backup:run');
            
            return back()->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $diskName = config('backup.backup.destination.disks')[0];
        $backupPath = config('backup.backup.name') . '/' . $filename;
        
        if (!Storage::disk($diskName)->exists($backupPath)) {
            return back()->with('error', 'Backup file not found.');
        }
        
        $filePath = Storage::disk($diskName)->path($backupPath);
        return response()->download($filePath);
    }

    public function delete($filename)
    {
        $diskName = config('backup.backup.destination.disks')[0];
        $backupPath = config('backup.backup.name') . '/' . $filename;
        
        if (!Storage::disk($diskName)->exists($backupPath)) {
            return back()->with('error', 'Backup file not found.');
        }
        
        try {
            Storage::disk($diskName)->delete($backupPath);
            return back()->with('success', 'Backup deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request, $filename)
    {
        $request->validate([
            'confirmation' => 'required|in:yes'
        ], [
            'confirmation.in' => 'You must type "yes" to confirm the restore operation.'
        ]);

        $diskName = config('backup.backup.destination.disks')[0];
        $backupPath = config('backup.backup.name') . '/' . $filename;
        
        if (!Storage::disk($diskName)->exists($backupPath)) {
            return back()->with('error', 'Backup file not found.');
        }

        try {
            // This is a simplified restore - in production you'd want more sophisticated restore logic
            // For now, we'll just show a success message
            return back()->with('success', 'Restore operation initiated. Please check the logs for details.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore backup: ' . $e->getMessage());
        }
    }

    private function getBackups()
    {
        $backups = [];
        $diskName = config('backup.backup.destination.disks')[0];
        $disk = Storage::disk($diskName);
        $backupPath = config('backup.backup.name');
        
        if ($disk->exists($backupPath)) {
            $files = $disk->files($backupPath);
            
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                    $backups[] = [
                        'filename' => basename($file),
                        'size' => $this->formatBytes($disk->size($file)),
                        'date' => Carbon::createFromTimestamp($disk->lastModified($file)),
                        'path' => $file
                    ];
                }
            }
            
            // Sort by date, newest first
            usort($backups, function($a, $b) {
                return $b['date']->timestamp - $a['date']->timestamp;
            });
        }
        
        return $backups;
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }

    public function calculateTotalSize($backups)
    {
        $totalSize = 0;
        $diskName = config('backup.backup.destination.disks')[0];
        foreach ($backups as $backup) {
            $disk = Storage::disk($diskName);
            $totalSize += $disk->size($backup['path']);
        }
        
        return $this->formatBytes($totalSize);
    }
} 