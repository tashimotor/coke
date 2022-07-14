<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Замена пароля на его хэш после валидации.
     *
     * @return void
     */
    protected function passedValidation(): void
    {
        $this->replace(['password' => Hash::make($this->get('password'))]);
        $this->merge(['register_init' => $this->cookie('register_page_first_open_date')]);
    }

    /**
     * При вызове validated будет отдаваться также и новые значения.
     *
     * @param $key
     * @param $default
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), [
            'password' => $this->input('password'),
            'register_init' => $this->input('register_init'),
        ]);
    }
}
