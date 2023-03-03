<?php

namespace A17\TwillRobotsTxt\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use A17\TwillRobotsTxt\Models\TwillRobotsTxt;
use A17\Twill\Http\Controllers\Admin\ModuleController;
use A17\TwillRobotsTxt\Repositories\TwillRobotsTxtRepository;
use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt as TwillRobotsTxtFacade;

class TwillRobotsTxtController extends ModuleController
{
    protected $moduleName = 'twillRobotsTxt';

    protected $titleColumnKey = 'domain_string';

    protected $titleFormKey = 'domain';

    protected $defaultOrders = ['domain' => 'asc'];

    protected $indexColumns = [
        'domain_string' => [
            'title' => 'Domain',
            'field' => 'domain_string',
        ],

        'status' => [
            'title' => 'Status',
            'field' => 'status',
        ],

        'from_dot_env' => [
            'title' => 'From .env',
            'field' => 'from_dot_env',
        ],
    ];

    /**
     * Options of the index view.
     *
     * @var array
     */
    protected $defaultIndexOptions = [
        'create' => true,
        'edit' => true,
        'publish' => true,
        'bulkPublish' => false,
        'feature' => false,
        'bulkFeature' => false,
        'restore' => true,
        'bulkRestore' => false,
        'forceDelete' => false,
        'bulkForceDelete' => false,
        'delete' => true,
        'duplicate' => true,
        'bulkDelete' => false,
        'reorder' => false,
        'permalink' => false,
        'bulkEdit' => false,
        'editInModal' => false,
        'skipCreateModal' => false,
        'includeScheduledInList' => false,
    ];

    /**
     * @param int|null $parentModuleId
     * @return array|\Illuminate\View\View|RedirectResponse
     */
    public function index($parentModuleId = null)
    {
        $this->generateDomains();

        $this->setIndexOptions();

        return parent::index($parentModuleId = null);
    }

    protected function getViewPrefix(): string|null
    {
        return 'twill-robots-txt::admin';
    }

    public function generateDomains(): void
    {
        if (DB::table('twill_robots_txt')->count() !== 0) {
            return;
        }

        $appDomain = TwillRobotsTxtFacade::getDomain(config('app.url'));

        $currentDomain = TwillRobotsTxtFacade::getDomain();

        if (filled($currentDomain)) {
            /** @phpstan-ignore-next-line  */
            app(TwillRobotsTxtRepository::class)->create([
                'domain' => $currentDomain,
                'published' => true,
                'protected' => TwillRobotsTxtFacade::config('contents.default.protected'),
                'unprotected' => TwillRobotsTxtFacade::config('contents.default.unprotected'),
            ]);
        }

        if (filled($appDomain) && $appDomain !== $currentDomain) {
            /** @phpstan-ignore-next-line  */
            app(TwillRobotsTxtRepository::class)->create([
                'domain' => $appDomain,
                'published' => true,
                'protected' => TwillRobotsTxtFacade::config('contents.default.protected'),
                'unprotected' => TwillRobotsTxtFacade::config('contents.default.unprotected'),
            ]);
        }
    }

    public function setIndexOptions(): void
    {
        $this->indexOptions = ['create' => !TwillRobotsTxtFacade::allDomainsPublished()];
    }
}
