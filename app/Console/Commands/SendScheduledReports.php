<?php

namespace App\Console\Commands;

use App\Models\ScheduledReport;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserActivityReportMail;
use Carbon\Carbon;

class SendScheduledReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan for scheduled reports that are due and send them.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Checking for scheduled reports to send...');

        $reports = ScheduledReport::all();

        foreach ($reports as $report) {
            $isDue = $this->isReportDue($report);

            if ($isDue) {
                Log::info("Report ID {$report->id} is due. Generating and sending.");
                
                $data = $this->generateReportData($report);
                $recipients = array_map('trim', explode(',', $report->recipients));

                Mail::to($recipients)->send(new UserActivityReportMail($data));

                $report->update(['last_sent_at' => now()]);
                
                Log::info("Report ID {$report->id} sent successfully.");
            }
        }

        $this->info('Scheduled reports check complete.');
        return 0;
    }

    private function isReportDue(ScheduledReport $report): bool
    {
        $lastSent = $report->last_sent_at ? Carbon::parse($report->last_sent_at) : null;

        if (!$lastSent) {
            return true; // Never sent, so it's due.
        }

        switch ($report->frequency) {
            case 'daily':
                return $lastSent->diffInDays(now()) >= 1;
            case 'weekly':
                return $lastSent->diffInWeeks(now()) >= 1;
            case 'monthly':
                return $lastSent->diffInMonths(now()) >= 1;
            default:
                return false;
        }
    }

    private function generateReportData(ScheduledReport $report): array
    {
        $now = now();
        $startOfPeriod = $this->getStartDateForFrequency($report->frequency, $now);

        if ($report->report_type === 'user_activity') {
            return [
                'period' => $this->getPeriodLabel($report->frequency),
                'new_users' => User::where('created_at', '>=', $startOfPeriod)->count(),
                'approved_users' => User::where('status', 'approved')->where('updated_at', '>=', $startOfPeriod)->count(),
                'pending_users' => User::where('status', 'pending')->count(),
                'total_users' => User::where('status', 'approved')->count(),
            ];
        }

        // Placeholder for other report types
        return [];
    }
    
    private function getStartDateForFrequency(string $frequency, Carbon $now): Carbon
    {
        return match ($frequency) {
            'daily' => $now->copy()->subDay(),
            'weekly' => $now->copy()->subWeek(),
            'monthly' => $now->copy()->subMonth(),
            default => $now,
        };
    }

    private function getPeriodLabel(string $frequency): string
    {
        return match ($frequency) {
            'daily' => 'last 24 hours',
            'weekly' => 'last 7 days',
            'monthly' => 'last month',
            default => 'the relevant period',
        };
    }
}
