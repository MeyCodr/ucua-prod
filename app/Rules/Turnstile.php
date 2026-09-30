<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Checks a Cloudflare Turnstile token (the cf-turnstile-response field) with Cloudflare.
 */
class Turnstile implements Rule
{
    public function passes($attribute, $value)
    {
        $secret = config('services.turnstile.secret');

        if (!$secret) {
            Log::error('Turnstile: TURNSTILE_SECRET_KEY is not set.');
            return false;
        }

        if (!is_string($value) || $value === '') {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(10)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Turnstile: verification request failed. ' . $e->getMessage());
            return false;
        }

        if (!$response->json('success')) {
            Log::warning('Turnstile: verification rejected.', ['errors' => $response->json('error-codes')]);
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'Security check failed, please tick the verification box and submit again. / Pengesahan keselamatan gagal, sila sahkan dan hantar semula.';
    }
}
