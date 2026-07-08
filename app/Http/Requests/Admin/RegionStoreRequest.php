<?php

namespace App\Http\Requests\Admin;

use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class RegionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique(Region::class, 'name')],

            // Regional Admin account fields
            'ra_name' => ['required', 'string', 'max:255'],
            'ra_email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'ra_password' => ['required', 'confirmed', Rules\Password::defaults()],
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
