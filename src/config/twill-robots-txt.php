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
    'protected' => ($isProtected = env('TWILL_ROBOTS_TXT_PROTECTED', false)),

    'contents' => [
        'protected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_PROTECTED_CONTENTS', $isProtected ? $protected : null)),

        'unprotected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_UNPROTECTED_CONTENTS', $isProtected ? $unprotected : null)),

        'default' => [
            'protected' => $protected,

            'unprotected' => $unprotected,

        ],
    ],

    'route' => [
        'controller' => A17\TwillRobotsTxt\Http\Controllers\TwillRobotsTxtFrontController::class,
        'action' => 'robots',
    ],

    'rate-limiting' => [
        'attemps-per-minute' => env('TWILL_ROBOTS_TXT_RATE_LIMITING_ATTEMPTS', 500),
    ],
];
