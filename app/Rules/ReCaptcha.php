<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //Validacion del token de reCaptcha
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            "secret" => '6LdOYV8pAAAAAL4MQQ1IaFhb-PYvkNuIOjeMsdbd',
            "response"=> $value->input('g-recaptcha-response'),
        ])->object();

        if($response->success && $response->score >= 0.7) {

        } else {

        }
    }
}
