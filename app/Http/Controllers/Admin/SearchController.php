<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['users' => [], 'pages' => []]);
        }

        // Search for users
        $users = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('company', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'name', 'email', 'role']);

        // Search for pages
        $allPages = [
            ['name' => 'User Management', 'url' => route('admin.user-management'), 'icon' => 'fa-users-cog'],
            ['name' => 'User Validation', 'url' => route('admin.user-validation'), 'icon' => 'fa-user-check'],
            ['name' => 'Analytics', 'url' => route('admin.analytics'), 'icon' => 'fa-chart-line'],
            ['name' => 'Reports', 'url' => route('admin.reports'), 'icon' => 'fa-file-alt'],
            ['name' => 'Settings', 'url' => route('admin.settings'), 'icon' => 'fa-cog'],
            ['name' => 'Backups', 'url' => route('admin.backups'), 'icon' => 'fa-hdd'],
            ['name' => 'System Flow', 'url' => route('admin.system-flow'), 'icon' => 'fa-project-diagram'],
        ];

        $pages = collect($allPages)->filter(function ($page) use ($query) {
            return stripos($page['name'], $query) !== false;
        });

        return response()->json(['users' => $users, 'pages' => $pages->values()]);
    }
}
