<?php namespace MH\Http\Requests;

use MH\Http\Requests\Request;

/**
 * Class StoreBlogPostRequest
 * @package MH\Http\Requests
 */
class StoreBlogPostRequest extends Request
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
            'excerpt' => 'required',
            'content' => 'required',
            'featured' => 'required',
            'status' => 'required',
        ];
    }
}
