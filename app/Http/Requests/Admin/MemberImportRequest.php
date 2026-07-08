<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MemberImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
            'club_id' => ['required', 'integer', 'exists:clubs,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'club_id' => 'club',
        ];
    }
}
