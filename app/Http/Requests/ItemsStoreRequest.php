<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read \App\Models\User $user
 * @property-read string $name
 * @property-read int $price
 * @property-read string $description
 * @property-read string $categories
 * @property-read string $condition
 * @property-read \Illuminate\Http\UploadedFile $image
 */
final class ItemsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string', 'max:1000'],
            'categories' => ['required', 'string', 'max:255'],
            'condition' => ['required', 'string', 'exists:conditions,name'],
            'image' => ['required', 'image', 'mimes:jpg,png', 'max:2048'],
        ];
    }
}
