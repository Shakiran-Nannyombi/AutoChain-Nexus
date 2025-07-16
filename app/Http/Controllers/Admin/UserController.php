<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AdminActivity;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('documents')->where('role', '!=', 'admin');

        // Handle search
        if ($request->has('search') && $request->input('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('company', 'like', "%{$searchTerm}%");
            });
        }

        // Handle role filter
        if ($request->has('role') && $request->input('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->get();

        $stats = [
            'total' => User::where('role', '!=', 'admin')->count(),
            'active' => User::where('role', '!=', 'admin')->where('status', 'approved')->count(),
            'inactive' => User::where('role', '!=', 'admin')->where('status', '!=', 'approved')->count(),
            'new_this_month' => User::where('role', '!=', 'admin')->where('created_at', '>=', Carbon::now()->startOfMonth())->count(),
        ];

        return view('dashboards.admin.user-management', [
            'users' => $users,
            'stats' => $stats,
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    public function validation()
    {
        $pendingUsers = User::with('documents')->where('status', 'pending')->get();
        
        $stats = [
            'pending' => $pendingUsers->count(),
            'approved_this_week' => User::where('status', 'approved')
                                        ->where('updated_at', '>=', Carbon::now()->subWeek())
                                        ->count(),
            'rejected_this_week' => User::where('status', 'rejected')
                                        ->where('updated_at', '>=', Carbon::now()->subWeek())
                                        ->count(),
        ];

        return view('dashboards.admin.user-validation', [
            'pendingUsers' => $pendingUsers,
            'stats' => $stats,
        ]);
    }

    public function approve(User $user)
    {
        $user->status = 'approved';
        $user->save();

        try {
            $subject = "Your Application has been Approved!";
            $loginUrl = url('/login');
            $body = "Dear {$user->name},\n\nCongratulations! Your account for the Autochain Nexus platform has been approved.\n\nYou can now log in and access all the features available to you.\n\nLogin here: {$loginUrl}\n\nBest Regards,\nThe Autochain Nexus Team";
            
            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $user->email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for approval of {$user->email}: " . $e->getMessage());
            return back()->with('error', 'User approved, but failed to send notification email.');
        }
        
        // Log admin activity
        AdminActivity::create([
            'admin_id' => session('user_id'),
            'action' => 'approved user',
            'details' => 'Approved user ID: ' . $user->id . ' (' . $user->name . ')',
        ]);
        
        return back()->with('success', "User '{$user->name}' has been approved and notified.");
    }

    public function reject(User $user)
    {
        $user->status = 'rejected';
        $user->save();

        try {
            $subject = "Update on Your Application Status";
            $body = "Dear {$user->name},\n\nThank you for your interest in the Autochain Nexus platform. After careful review, we regret to inform you that your application has been rejected at this time.\n\nIf you believe this was in error or have further questions, please contact our support team.\n\nBest Regards,\nThe Autochain Nexus Team";

            Http::post('http://localhost:8082/api/v1/send-email', [
                'to' => $user->email,
                'subject' => $subject,
                'body' => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to call email service for rejection of {$user->email}: " . $e->getMessage());
            return back()->with('error', 'User rejected, but failed to send notification email.');
        }
        
        // Log admin activity
        AdminActivity::create([
            'admin_id' => session('user_id'),
            'action' => 'rejected user',
            'details' => 'Rejected user ID: ' . $user->id . ' (' . $user->name . ')',
        ]);
        
        return back()->with('success', "User '{$user->name}' has been rejected and notified.");
    }

    public function show(User $user)
    {
        // Eager load documents for the specific user
        $user->load('documents');
        return response()->json($user);
    }

    public function edit(User $user)
    {
        return view('dashboards.admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:manufacturer,supplier,vendor,retailer,analyst',
            'status' => 'required|in:pending,approved,rejected',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.user-management')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User has been deleted.');
    }

    public function runValidation(User $user)
    {
        // Eager load the documents to ensure we have the paths
        $user->load('documents');

        // Prepare the data payload for the Java API
        $vendorData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'company' => $user->company,
            'phone' => $user->phone,
            'address' => $user->address,
            'documentPaths' => $user->documents->pluck('file_path')->all(),
        ];

        try {
            // Make the HTTP request to the validation API
            $response = Http::post('http://localhost:8084/api/v1/validate', $vendorData);

            if ($response->successful()) {
                $result = $response->json();
                
                // Update the user's validation scores
                $user->validation_score = $result['score'] ?? 0;
                $user->financial_score = $result['financial_score'] ?? 0;
                $user->reputation_score = $result['reputation_score'] ?? 0;
                $user->compliance_score = $result['compliance_score'] ?? 0;
                $user->profile_score = $result['profile_score'] ?? 0;
                $user->extracted_data = $result['extracted_data'] ?? null;
                $user->validated_at = now();
                $user->save();
                
                // Check if validation score meets threshold for automatic visit scheduling
                $validationThreshold = 70; // Minimum score to pass validation
                $visitScheduled = false;
                
                if ($user->role === 'vendor' && $user->validation_score >= $validationThreshold && !$user->auto_visit_scheduled) {
                    // Set vendor status to pending_visit (not approved)
                    $user->status = 'pending_visit';
                    $user->auto_visit_scheduled = true;
                    $user->save();
                    // Automatically schedule a facility visit for vendors
                    $visitScheduled = $this->scheduleAutomaticVisit($user);
                }
                
                $message = "Validation complete for {$user->name}. Score: {$user->validation_score}. ";
                if ($visitScheduled) {
                    $message .= "Facility visit automatically scheduled.";
                } elseif ($user->validation_score >= $validationThreshold) {
                    $message .= "Validation passed. Visit scheduling recommended.";
                } else {
                    $message .= "Validation failed. Score below threshold ({$validationThreshold}).";
                }
                
                return back()->with('success', $message);
            } else {
                Log::error('Validation API request failed', [
                    'status' => $response->status(), 
                    'body' => $response->body()
                ]);
                return back()->with('error', 'Validation request failed. Check system logs.');
            }
        } catch (\Exception $e) {
            Log::error('Could not connect to validation API', ['error' => $e->getMessage()]);
            return back()->with('error', 'Could not connect to the validation service.');
        }
    }

    /**
     * Automatically schedule a facility visit for a validated vendor.
     * @param User $user The validated user
     * @return bool True if visit was scheduled successfully
     */
    private function scheduleAutomaticVisit(User $user)
    {
        try {
            // Create a facility visit request
            $visit = \App\Models\FacilityVisit::create([
                'user_id' => $user->id,
                'company_name' => $user->company ?? $user->name,
                'contact_person' => $user->name,
                'contact_email' => $user->email,
                'visit_date' => now()->addDays(14)->setTime(10, 0), // Schedule 2 weeks from now at 10 AM
                'purpose' => 'Post-validation facility assessment for vendor onboarding',
                'location' => $user->address ?? 'To be determined',
                'visit_type' => 'Assessment',
                'status' => 'pending',
                'requested_date' => now(),
            ]);

            // Send notification email about the scheduled visit (pending visit email)
            try {
                $subject = "Facility Visit Scheduled: {$user->company}";
                $body = "Dear {$user->name},\n\nCongratulations! Your vendor validation has been completed successfully.\n\nA facility visit has been automatically scheduled for {$visit->visit_date->format('Y-m-d \\a\\t h:i A')}.\n\nPurpose: {$visit->purpose}\nLocation: {$visit->location}\n\nYou will be approved as a vendor after a successful facility visit.\n\nBest Regards,\nThe Autochain Nexus Team";
                
                Http::post('http://localhost:8082/api/v1/send-email', [
                    'to' => $user->email,
                    'subject' => $subject,
                    'body' => $body,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to send visit scheduling email to {$user->email}: " . $e->getMessage());
            }

            // Log admin activity
            AdminActivity::create([
                'admin_id' => session('user_id'),
                'action' => 'scheduled visit',
                'details' => 'Scheduled visit for vendor ID: ' . $user->id . ' (' . $user->name . ')',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to schedule automatic visit for user {$user->id}: " . $e->getMessage());
            return false;
        }
    }
}
