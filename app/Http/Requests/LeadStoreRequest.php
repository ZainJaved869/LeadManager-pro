<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:100'],
            'stage_id' => ['nullable', 'exists:pipeline_stages,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'contacted_at' => ['nullable', 'date'],
            'won_at' => ['nullable', 'date'],
            'lost_reason' => ['nullable', 'string'],
            'custom_fields' => ['nullable', 'array'],
        ];
    }
}