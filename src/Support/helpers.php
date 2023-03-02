<?php

use A17\TwillRobotsTxt\Services\Helpers;
use A17\TwillRobotsTxt\Services\TwillRobotsTxt;

if (!function_exists('robots_txt')) {
    function robots_txt(): TwillRobotsTxt
    {
        return Helpers::instance();
    }
}
