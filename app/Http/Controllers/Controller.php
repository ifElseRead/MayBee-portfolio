<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function verifyRecaptcha(string $token): bool
    {
        $secret = config('services.recaptcha.secret');

        if (! $secret) {
            return false;
        }

        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);

        return $response->successful()
            && data_get($response->json(), 'success') === true
            && data_get($response->json(), 'score', 0) >= 0.5;
    }
}
