<?php

namespace App\Http\Requests;

use App\Rules\IsTimestamp;

class StorePostRequest extends ApiRequest
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
            "id"        => "required|max:255",
            "title"     => "required|max:255|nullable",
            "content"   => "max:1000|nullable",
            "views"     => "integer|nullable",
            "timestamp" => ["nullable", new IsTimestamp()],
        ];
    }
}
