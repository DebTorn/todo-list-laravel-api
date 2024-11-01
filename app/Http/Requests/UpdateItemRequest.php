<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'string|nullable',
            'list_id' => [
                'required',
                'integer',
                Rule::exists('lists', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::id());
                    $query->whereNull('deleted_at');
                }),
            ],
            'completed' => 'boolean|nullable',
            'background_color' => 'string|nullable',
            'background_id' => 'integer|nullable' # TODO: Add exists rule
        ];
    }
}
