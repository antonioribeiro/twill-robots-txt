<?php

namespace A17\TwillRobotsTxt\Support\Facades;

use Illuminate\Support\Facades\Facade;
use A17\TwillRobotsTxt\Support\TwillRobotsTxt as TwillRobotsTxtService;

class TwillRobotsTxt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwillRobotsTxtService::class;
    }
}
