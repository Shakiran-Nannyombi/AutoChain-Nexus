<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationStatusController extends Controller
{
    /**
     * Display the application status view.
     */
    public function index($id): View
    {
        $user = User::findOrFail($id);
        return view('application-status', ['user' => $user]);
    }
}
