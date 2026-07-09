<?php

namespace App\Http\Requests\Admin;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class RegionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Region $region */
        $region = $this->route('region');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Region::class, 'name')->ignore($region?->id),
            ],

            // Regional Admin account fields (optional on update)
            'ra_name' => ['sometimes', 'required', 'string', 'max:255'],
            'ra_email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Ignore the current regional admin user if one exists
                Rule::unique(User::class, 'email')->ignore($region?->regionalAdmin?->id),
            ],
            'ra_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            'ra_name' => 'regional admin name',
            'ra_email' => 'regional admin email',
            'ra_password' => 'regional admin password',
        ];
    }
}
