<?php

namespace App\Http\Requests\Admin;

use App\Models\Club;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class ClubUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Club $club */
        $club = $this->route('club');

        return [
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Club::class, 'name')->ignore($club?->id),
            ],

            // Club President account fields (optional on update)
            'cp_name' => ['sometimes', 'required', 'string', 'max:255'],
            'cp_email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Ignore the current club president user if one exists
                Rule::unique(User::class, 'email')->ignore($club?->clubPresident?->id),
            ],
            'cp_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
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
