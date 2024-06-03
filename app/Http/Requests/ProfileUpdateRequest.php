<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Stringable;

/**
 * @property-read string $name
 * @property-read string|null $postcode
 * @property-read string|null $prefecture
 * @property-read string|null $address
 * @property-read string|null $building
 * @property-read \Illuminate\Http\UploadedFile|null $image
 */
final class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|Stringable>>
     */
    public function rules(): array
    {
        assert($this->user() !== null);

        return [
            'name' => ['required', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'digits:7'],
            'prefecture' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
