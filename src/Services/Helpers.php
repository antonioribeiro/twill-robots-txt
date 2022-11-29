<?php

namespace A17\TwillRobotsTxt\Services;

use A17\TwillRobotsTxt\Support\TwillRobotsTxt;

class Helpers
{
    public static function load(): void
    {
        require __DIR__ . '/../Support/helpers.php';
    }

    public static function instance(): TwillRobotsTxt
    {
        if (!app()->bound('robots-txt')) {
            app()->singleton('robots-txt', fn() => new TwillRobotsTxt());
        }

        return app('robots-txt');
    }
}
