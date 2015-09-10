<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class CreateProjectRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'        => 'required|string|min:10|max:256',
            'description'  => 'required|string|min:30',
            'poster'       => 'string',
            'images'       => 'array',
            'active'       => ''
        ];
    }
}
