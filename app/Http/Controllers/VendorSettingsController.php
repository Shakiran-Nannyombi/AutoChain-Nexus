<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserDocument;
use App\Models\VendorActivity;
use Illuminate\Support\Facades\Storage;

class VendorSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user documents
        $documents = UserDocument::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get recent activities
        $recentActivities = VendorActivity::where('vendor_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
        
        // Get notification preferences (if implemented)
        $notificationPreferences = [
            'email_notifications' => true,
            'order_updates' => true,
            'delivery_status' => true,
            'stock_alerts' => true,
            'system_updates' => false
        ];
        
        // Get account statistics
        $accountStats = [
            'total_products' => \App\Models\Product::where('vendor_id', $user->id)->count(),
            'total_orders' => \App\Models\VendorOrder::where('vendor_id', $user->id)->count(),
            'total_deliveries' => \App\Models\RetailerStock::where('vendor_id', $user->id)->count(),
            'account_age' => $user->created_at->diffInDays(now())
        ];
        
        return view('dashboards.vendor.settings', compact(
            'user',
            'documents',
            'recentActivities',
            'notificationPreferences',
            'accountStats'
        ));
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);
        
        $user->update($validated);
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Updated profile',
            'details' => 'Profile information updated',
        ]);
        
        return back()->with('success', 'Profile updated successfully.');
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Changed password',
            'details' => null,
        ]);
        
        return back()->with('success', 'Password changed successfully.');
    }
    
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
            'document_type' => 'required|string|in:profile_picture,supporting_document,business_license,insurance_certificate'
        ]);
        
        $user = Auth::user();
        
        foreach ($request->file('documents') as $document) {
            $documentName = time() . '_' . $document->getClientOriginalName();
            $documentPath = $document->storeAs('public/documents', $documentName);
            
            UserDocument::create([
                'user_id' => $user->id,
                'document_type' => $request->document_type,
                'file_path' => str_replace('public/', '', $documentPath)
            ]);
        }
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Uploaded documents',
            'details' => "Uploaded {$request->document_type} documents",
        ]);
        
        return back()->with('success', 'Documents uploaded successfully.');
    }
    
    public function deleteDocument($documentId)
    {
        $user = Auth::user();
        $document = UserDocument::where('user_id', $user->id)
            ->findOrFail($documentId);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Deleted document',
            'details' => "Deleted {$document->document_type} document",
        ]);
        
        return back()->with('success', 'Document deleted successfully.');
    }
    
    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'order_updates' => 'boolean',
            'delivery_status' => 'boolean',
            'stock_alerts' => 'boolean',
            'system_updates' => 'boolean',
        ]);
        
        // Store notification preferences (you might want to create a separate table for this)
        // For now, we'll store it in the user's extracted_data field
        $currentData = $user->extracted_data ?? [];
        $currentData['notification_preferences'] = $validated;
        $user->update(['extracted_data' => $currentData]);
        
        // Log activity
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Updated notification preferences',
            'details' => 'Notification settings updated',
        ]);
        
        return back()->with('success', 'Notification preferences updated successfully.');
    }
    
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DEACTIVATE',
            'reason' => 'required|string|max:500'
        ]);
        
        $user = Auth::user();
        
        // Log activity before deactivation
        VendorActivity::create([
            'vendor_id' => $user->id,
            'activity' => 'Account deactivation requested',
            'details' => "Reason: {$request->reason}",
        ]);
        
        // Update user status to deactivated
        $user->update(['status' => 'deactivated']);
        
        // Logout the user
        Auth::logout();
        session()->flush();
        
        return redirect()->route('login')
            ->with('success', 'Your account has been deactivated. Contact support to reactivate.');
    }
} 