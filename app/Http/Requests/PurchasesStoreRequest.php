<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $postcode
 * @property-read string $address
 * @property-read string|null $building
 */
final class PurchasesStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'postcode' => 'required|string|digits:7',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }
}
