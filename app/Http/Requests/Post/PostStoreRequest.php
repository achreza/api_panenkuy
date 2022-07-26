<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'contact_person' => 'required|array',
            'location' => 'required',
            'price' => 'required|integer',
            'expired_time' => 'required|date',
            'created_by' => 'required',
            'picture' => 'nullable|mimes:png,jpg'
        ];
    }
}
