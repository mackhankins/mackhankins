<?php namespace MH\Http\Requests;

use MH\Http\Requests\Request;

class UpdateBlogPostRequest extends Request
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
            'slug' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
            'status' => 'required',
        ];
    }
}
