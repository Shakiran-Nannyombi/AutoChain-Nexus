<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ValidationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ValidationCriteriaController extends Controller
{
    public function index()
    {
        $rules = ValidationRule::orderBy('category')->orderBy('name')->get();
        return view('dashboards.admin.validation-criteria', [
            'rules' => $rules
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'value' => 'required|string|max:255',
        ]);

        // Prevent duplicate rule creation
        $exists = ValidationRule::where('name', $request->name)
            ->where('category', $request->category)
            ->where('value', $request->value)
            ->exists();
        if ($exists) {
            return back()->with('error', 'A validation rule with the same name, category, and value already exists.');
        }

        ValidationRule::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'value' => $request->value,
            'status' => 'active', // Default to active for now
        ]);

        // Automatically sync after adding a new rule
        $this->syncRules();

        return back()->with('success', 'Validation rule added and synced successfully!');
    }

    public function syncRules()
    {
        $rules = ValidationRule::where('status', 'active')->get();

        try {
            $response = Http::post('http://localhost:8084/api/v1/sync-rules', $rules->toArray());

            if ($response->successful()) {
                return back()->with('success', 'Rules synced with backend successfully!');
            } else {
                return back()->with('error', 'Failed to sync rules with backend. API response: ' . $response->body());
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Could not connect to the validation backend. Please ensure it is running. Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, ValidationRule $rule)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'value' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $rule->update($data);

        // Automatically sync after updating a rule
        $this->syncRules();

        return back()->with('success', 'Validation rule updated and synced successfully!');
    }

    public function destroy(ValidationRule $rule)
    {
        $rule->delete();

        // Automatically sync after deleting a rule
        $this->syncRules();

        return back()->with('success', 'Validation rule deleted and synced successfully!');
    }
}
