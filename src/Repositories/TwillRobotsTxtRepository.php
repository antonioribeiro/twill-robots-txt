<?php

namespace A17\TwillRobotsTxt\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\TwillRobotsTxt\Models\TwillRobotsTxt;

/**
 * @method \Illuminate\Database\Eloquent\Builder published()
 */
class TwillRobotsTxtRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillRobotsTxt $model)
    {
        $this->model = $model;
    }
}
