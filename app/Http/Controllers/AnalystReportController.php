<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalystReport;

class AnalystReportController extends Controller
{
    public function index()
    {
        $reports = AnalystReport::latest()->paginate(10);
        return view('dashboards.analyst.reports', compact('reports'));
    }

    public function create()
    {
        return view('dashboards.analyst.create-reports');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'target_role' => 'required',
            'summary' => 'required|string',
            'report_file' => 'nullable|file|mimes:pdf,xlsx,docx'
        ]);

        $filePath = null;
        if ($request->hasFile('report_file')) {
            $filePath = $request->file('report_file')->store('reports', 'public');
        }

        AnalystReport::create([
            'title' => $request->title,
            'type' => $request->type,
            'target_role' => $request->target_role,
            'summary' => $request->summary,
            'report_file' => $filePath,
        ]);

        return redirect()->route('analyst.reports')->with('success', 'Report generated.');
    }
}

