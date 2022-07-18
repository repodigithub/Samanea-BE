<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Zttp\Zttp;

class Recaptcha implements Rule
{
    const URL = 'https://www.google.com/recaptcha/api/siteverify';
    
    /**
    * Determine if the validation rule passes.
    *
    * @param  string  $attribute
    * @param  mixed  $value
    * @return bool
    */
    public function passes($attribute, $value)
    {
        return Zttp::asFormParams()->post(static::URL, [
            'secret' => config('captcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip()
            ])->json()['success'];

    }

    public function message()
    {
        return 'The recaptcha verification failed. Try again.';
    }

}