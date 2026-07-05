<?php

namespace App\Http\Requests\Admin;

use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;
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
        ];
    }
}
