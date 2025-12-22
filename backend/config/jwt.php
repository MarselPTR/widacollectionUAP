<?php

return [
    // HS256 secret. If JWT_SECRET is not set, we fall back to APP_KEY.
    'secret' => env('JWT_SECRET', null),

    // Token issuer (iss)
    'issuer' => env('APP_URL', 'http://localhost'),

    // Token time-to-live (minutes)
    'ttl_minutes' => (int) env('JWT_TTL_MINUTES', 120),

    // Leeway (seconds) for clock skew
    'leeway_seconds' => (int) env('JWT_LEEWAY_SECONDS', 30),
];
