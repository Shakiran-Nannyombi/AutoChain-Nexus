<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// use App\Mail\UserStatusNotification;

class UserApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')
            ->with('documents')
            ->get();

        return response()->json($pendingUsers);
    }

    public function approve(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => ['required_if:action,reject', 'string', 'max:1000'],
            'action' => ['required', 'in:approve,reject'],
        ]);

        if ($request->action === 'approve') {
            $user->update([
                'status' => 'approved',
                'rejection_reason' => null,
            ]);

            // Mail::to($user->email)->send(new UserStatusNotification($user, 'approved'));
        } else {
            $user->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Mail::to($user->email)->send(new UserStatusNotification($user, 'rejected', $request->rejection_reason));
        }

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }
} 