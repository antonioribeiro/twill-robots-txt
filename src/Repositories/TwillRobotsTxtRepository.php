<?php

namespace A17\TwillRobotsTxt\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use A17\TwillRobotsTxt\Models\TwillRobotsTxt;
use A17\Twill\Repositories\Behaviors\HandleRevisions;

/**
 * @method bool published()
 * @method \Illuminate\Database\Eloquent\Builder orderBy(\Closure|\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Expression|string  $column, string  $direction = 'asc')
 */
class TwillRobotsTxtRepository extends ModuleRepository
{
    use HandleRevisions;

    public function __construct(TwillRobotsTxt $model)
    {
        $this->model = $model;
    }
}
