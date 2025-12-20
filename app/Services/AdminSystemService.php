<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Helpers\Format;

class AdminSystemService
{
    /**
     * Get all system settings.
     */
    public function getSettings()
    {
        return Setting::all()->pluck('value', 'key');
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $settings = $request->except(['_token', '_method']);

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }

    /**
     * Get all backups with metadata.
     */
    public function getBackups()
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
    
        return $backups;
    }

    /**
     * Create a new database backup.
     */
    public function createBackup()
    {
        Artisan::call('backup:run', ['--only-db' => true]);
    }
}
