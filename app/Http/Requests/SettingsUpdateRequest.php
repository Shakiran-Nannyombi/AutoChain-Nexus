<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email_notifications' => ['boolean'],
            'inventory_alerts' => ['boolean'],
            'production_updates' => ['boolean'],
            'vendor_communications' => ['boolean'],
            'report_generation' => ['boolean'],
            'two_factor_authentication' => ['boolean'],
            'time_zone' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:255'],
            'date_format' => ['nullable', 'string', 'max:255'],
            'dark_mode' => ['boolean'],
            'auto_refresh_dashboard' => ['boolean'],
        ];
    }
}
