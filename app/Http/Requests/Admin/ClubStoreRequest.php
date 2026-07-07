<?php

namespace App\Http\Requests\Admin;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class ClubStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique(Club::class, 'name')],

            // Club President account fields
            'cp_name' => ['required', 'string', 'max:255'],
            'cp_email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'cp_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            'cp_name' => 'club president name',
            'cp_email' => 'club president email',
            'cp_password' => 'club president password',
        ];
    }
}
