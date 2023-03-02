<?php

namespace A17\TwillRobotsTxt\Http\Controllers;

use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt;

class TwillRobotsTxtFrontController
{
    public function robots(): \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        return response(TwillRobotsTxt::robotsTxt(), 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
