<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailyReportRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'omset' => 'required|numeric|min:0',
            'activities' => 'required|array|min:1',
            'activities.*.activity_type_id' => 'required|exists:activity_types,id',
            'activities.*.description' => 'required|string|max:255',
            'activities.*.amount' => 'required|numeric|min:0',
            'offers' => 'nullable|array',
            'offers.*.id' => 'required|exists:offers,id',
            'offers.*.is_prospect' => 'boolean',
            'offers.*.updates' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'omset.required' => 'Daily omset is required',
            'omset.numeric' => 'Daily omset must be a number',
            'omset.min' => 'Daily omset cannot be negative',
            'activities.required' => 'At least one activity is required',
            'activities.*.activity_type_id.required' => 'Activity type is required',
            'activities.*.description.required' => 'Activity description is required',
            'activities.*.amount.required' => 'Activity amount is required',
            'activities.*.amount.numeric' => 'Activity amount must be a number',
            'offers.*.id.exists' => 'Selected offer does not exist',
        ];
    }
}