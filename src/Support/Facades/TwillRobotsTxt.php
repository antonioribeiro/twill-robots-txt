<?php

namespace A17\TwillRobotsTxt\Support\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use A17\TwillRobotsTxt\Services\TwillRobotsTxt as TwillRobotsTxtService;

/**
 * @method static string|null getDomain(string|null $url = null)
 * @method static mixed config(string $domain)
 * @method static bool allDomainsPublished()
 */
class TwillRobotsTxt extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TwillRobotsTxtService::class;
    }
}
