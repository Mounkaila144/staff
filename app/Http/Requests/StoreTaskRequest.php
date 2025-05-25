<?php
// app/Http/Requests/StoreTaskRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'end_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'required|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'end_date.required' => 'La date d\'échéance est obligatoire',
            'end_date.after_or_equal' => 'La date ne peut pas être dans le passé',
            'assigned_to.exists' => 'Le stagiaire sélectionné n\'existe pas'
        ];
    }
}
