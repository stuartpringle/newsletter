<?php

return [
    // Rate limiting for signup attempts per IP.
    'rate_limit' => [
        'max_attempts' => 5,
        'decay_seconds' => 60,
    ],

    // Honeypot field name used in the signup form.
    'honeypot_field' => 'name',

    // Confirmation email subject.
    'confirmation_subject' => 'Confirm Newsletter',

    // Tenant defaults
    'tenant' => [
        'default_tenant_id' => null,
    ],

    // Asset container for newsletter images.
    'assets_container' => 'newsletter',

    // Tracking and webhook behavior.
    'tracking' => [
        'enabled' => true,
    ],
];
