<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacilityVisit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VisitController extends Controller
{
    public function index()
    {
        $visits = FacilityVisit::whereHas('user', function ($query) {
            $query->where('role', 'vendor');
        })->with('user')->orderBy('visit_date', 'desc')->get();
        
        $allVisits = FacilityVisit::all();

        $stats = [
            'pending' => $visits->where('status', 'pending')->count(),
            'approved' => $visits->where('status', 'approved')->count(),
            'rejected' => $visits->where('status', 'rejected')->count(),
            'this_week' => $visits->where('status', 'completed')->count(),
            'completed' => $visits->where('status', 'completed')->count(),
            'auto_scheduled' => User::where('auto_visit_scheduled', true)->where('role', 'vendor')->count(),
        ];

        return view('dashboards.admin.visit-scheduling', compact('visits', 'stats'));
    }

    public function approve(FacilityVisit $visit)
    {
        $visit->status = 'approved';
        $visit->save();

        try {
            $subject = "Visit Request Approved: {$visit->company_name}";
            $body = "Dear {$visit->contact_person},\n\nThis is to confirm that your facility visit request for {$visit->visit_date->format('Y-m-d \a\t h:i A')} has been approved.\n\nPurpose: {$visit->purpose}\nLocation: {$visit->location}\n\nWe look forward to your visit.\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $visit->contact_email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for visit approval of {$visit->contact_email}: " . $e->getMessage());
            return back()->with('error', 'Visit approved, but failed to send notification email.');
        }

        return back()->with('success', 'Visit has been approved and a notification email has been sent.');
    }

    public function reject(FacilityVisit $visit)
    {
        $visit->status = 'rejected';
        $visit->save();

        try {
            $subject = "Update on Your Visit Request: {$visit->company_name}";
            $body = "Dear {$visit->contact_person},\n\nThis email is to inform you that your facility visit request for {$visit->visit_date->format('Y-m-d')} has been rejected.\n\nIf you have any questions or wish to appeal this decision, please contact our support team.\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $visit->contact_email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for visit rejection of {$visit->contact_email}: " . $e->getMessage());
            // We don't want to block the rejection if the email fails.
        }

        return back()->with('success', 'Visit has been rejected.');
    }

    public function sendConfirmationEmail(FacilityVisit $visit)
    {
        if ($visit->status !== 'approved') {
            return back()->with('error', 'You can only send confirmations for approved visits.');
        }

        try {
            $subject = "Confirmation of Your Upcoming Visit with Autochain Nexus";
            $body = "Dear {$visit->contact_person},\n\nThis is a friendly reminder confirming your upcoming facility visit scheduled for {$visit->visit_date->format('Y-m-d \a\t h:i A')}.\n\nPurpose: {$visit->purpose}\nLocation: {$visit->location}\n\nIf you have any questions, please don't hesitate to contact us.\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $visit->contact_email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for visit confirmation of {$visit->contact_email}: " . $e->getMessage());
            return back()->with('error', 'Failed to send confirmation email.');
        }

        return back()->with('success', 'Confirmation email has been sent successfully.');
    }

    public function reschedule(Request $request, FacilityVisit $visit)
    {
        $request->validate([
            'visit_date' => 'required|date|after:today',
            'visit_time' => 'required|date_format:H:i',
        ]);

        $visit->visit_date = \Carbon\Carbon::parse($request->visit_date . ' ' . $request->visit_time);
        $visit->save();

        try {
            $subject = "Visit Rescheduled: {$visit->company_name}";
            $body = "Dear {$visit->contact_person},\n\nYour facility visit has been rescheduled to {$visit->visit_date->format('Y-m-d \a\t h:i A')}.\n\nPurpose: {$visit->purpose}\nLocation: {$visit->location}\n\nIf you have any questions, please don't hesitate to contact us.\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $visit->contact_email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for visit reschedule of {$visit->contact_email}: " . $e->getMessage());
        }

        return back()->with('success', 'Visit has been rescheduled and notification email sent.');
    }

    public function complete(FacilityVisit $visit)
    {
        $visit->status = 'completed';
        $visit->save();

        // If the visit is for a vendor, approve the user
        $user = $visit->user;
        if ($user && $user->role === 'vendor' && $user->status !== 'approved') {
            $user->status = 'approved';
            $user->save();
            // Send approval email
            try {
                $subject = "Your Application has been Approved!";
                $loginUrl = url('/login');
                $body = "Dear {$user->name},\n\nCongratulations! Your account for the Autochain Nexus platform has been approved after a successful facility visit.\n\nYou can now log in and access all the features available to you.\n\nLogin here: {$loginUrl}\n\nBest Regards,\nThe Autochain Nexus Team";
                Http::post('http://localhost:8082/api/v1/send-email', [
                    'to' => $user->email,
                    'subject' => $subject,
                    'body' => $body,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to send approval email to vendor after visit completion: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Visit marked as completed. Vendor has been approved.');
    }
}
