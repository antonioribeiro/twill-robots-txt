<?php

$protected = <<<TXT
User-agent: *
Disallow: /
TXT;

$unprotected = <<<TXT
User-agent: *
Allow: /
TXT;

return [
    'protected' => env('TWILL_ROBOTS_TXT_PROTECTED', true),

    'contents' => [
        'protected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_PROTECTED_CONTENTS', $protected)),
        'unprotected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_UNPROTECTED_CONTENTS', $unprotected)),
    ],

    'route' => [
        'controller' => A17\TwillRobotsTxt\Http\Controllers\TwillRobotsTxtFrontController::class,
        'action' => 'robots',
    ],

    'rate-limiting' => [
        'attemps-per-minute' => env('TWILL_ROBOTS_TXT_RATE_LIMITING_ATTEMPTS', 500),
    ],
];
