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
    'enabled' => env('TWILL_ROBOTS_TXT_ENABLED', false),

    'defaults' => [
        'protected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_PROTECTED', $protected)),
        'unprotected' => str_replace('\n', "\n", env('TWILL_ROBOTS_TXT_UNPROTECTED', $unprotected)),
    ],

    'route' => [
        'controller' => A17\TwillRobotsTxt\Http\Controllers\TwillRobotsTxtFrontController::class,
        'action' => 'robots',
    ],
];
