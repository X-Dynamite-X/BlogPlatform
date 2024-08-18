<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if(auth()->id() === $this->post->user_id ){
        //     return true;
        // }
        // return false;
        return auth()->id() === $this->post->user_id ;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            //
            'title' => 'required|min:3|string|max:50',
            'content' => 'required|min:100|string|max:3000',
            'category_id' => 'required|exists:categories,id',
            "tags"=>"array",
            'tags.*' => 'exists:tags,id'
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required',
            'title.max' => 'The title must be between 3 and 50 characters',
            'title.min' => 'The title must be between 3 and 50 characters',
            'content.required' => 'The content field is required',
            'content.max' => 'The content must be between 100 and 3000 characters',
            'content.min' => 'The content must be between 100 and 3000 characters',
            'category_id.required' => 'The category field is required',
            'category_id.exists' => 'The category  is note exists',
        ];
    }
}
