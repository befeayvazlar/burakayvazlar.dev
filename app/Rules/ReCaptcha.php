<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'    => env('GOOGLE_RECAPTCHA_SECRET'),
            'response'  => $value
        ]);

        if ($response->successful() && $response->json('success') && $response->json('score') > 0.5) {
            return true;
        }

        return false;

        /*if (!$response['success']){
            return false;
        }
        if ($response['score'] < $this->threshold){
            return false;
        }*/

        //return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ReCaptcha doğrulanamadı! Tekrar deneyiniz...';
    }
}
