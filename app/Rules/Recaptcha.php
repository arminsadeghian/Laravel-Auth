<?php

namespace App\Rules;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\ValidationRule;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $client = new Client();
        $response = $client->request(
            'POST',
            'https://www.google.com/recaptcha/api/siteverify', [
            'query' => [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value
            ]
        ]);

        json_decode($response->getBody())->success;
    }
}
