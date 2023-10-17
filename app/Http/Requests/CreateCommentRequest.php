<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|max:255',
            'email'     => 'required|email|max:255',
//            'text'      => ['required', 'regex:/<(\/)?(a|code|i|strong)*>/'],
            'text'      => 'required',
            'homepage'  => 'url:http,https|max:255',
            'file'      => 'file|mimes:jpg,gif,png,txt',
            'captcha'   => 'required|captcha_api:'. request('key') . ',math'
        ];
    }
}
