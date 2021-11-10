<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PostsRequest extends FormRequest
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
        $image = Request::input('thumbnail')?Request::input('thumbnail'):'';
        
        return [
            'title'   => 'required|max:128',
            'thumbnail'   => 'image|mimes:jpeg,png,jpg,gif|max:1024'. $image,
            'status'   => 'required',
        ];
    }
}
