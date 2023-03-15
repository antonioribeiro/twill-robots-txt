<?php

namespace A17\TwillRobotsTxt\Services;

use Illuminate\Support\Arr;
use A17\RobotsTxt\RobotsTxt;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use A17\TwillRobotsTxt\Services\Cache;
use Illuminate\Support\Facades\RateLimiter;
use A17\TwillRobotsTxt\Models\Behaviors\Encrypt;
use A17\TwillRobotsTxt\Repositories\TwillRobotsTxtRepository;
use A17\TwillRobotsTxt\Models\TwillRobotsTxt as TwillRobotsTxtModel;

/**
 * @property string $protected
 * @property string $unprotected
 * @property bool $published
 */
class TwillRobotsTxt
{
    use Encrypt;
    use Cache;

    public const DEFAULT_ERROR_MESSAGE = 'Invisible captcha failed.';

    protected array|null $config = null;

    protected bool|null $isConfigured = null;

    protected bool|null $protected = null;

    protected TwillRobotsTxtModel|null $current = null;

    public function __construct()
    {
        $this->setConfigured();

        $this->setProtected();
    }

    public function config(string|null $key = null, mixed $default = null): mixed
    {
        $this->config ??= filled($this->config) ? $this->config : (array) config('twill-robots-txt');

        if (blank($key)) {
            return $this->config;
        }

        return Arr::get((array) $this->config, $key) ?? $default;
    }

    public function protected(): bool
    {
        return $this->protected ?? ($this->hasDotEnv() ? $this->config('protected') : true);
    }

    public function unprotectedContents(bool $force = false): string|null
    {
        return $this->get('contents.unprotected', 'unprotected', $force);
    }

    public function protectedContents(bool $force = false): string|null
    {
        return $this->get('contents.protected', 'protected', $force);
    }

    public function published(bool $force = false): string|null
    {
        return $this->get('protected', 'published', $force);
    }

    public function get(string $configKey, string $databaseColumn, bool $force = false): string|null
    {
        if (!$force && (!$this->isConfigured() || !$this->protected())) {
            return null;
        }

        return $this->hasDotEnv() ? $this->config($configKey) : $this->readFromDatabase($databaseColumn);
    }

    protected function readFromDatabase(string $key): string|bool|null
    {
        $domain = $this->getCurrent();

        if ($domain === null) {
            return null;
        }

        return $this->decrypt($domain->getAttributes()[$key]);
    }

    public function hasDotEnv(): bool
    {
        return $this->config('contents.enabled') === true &&
            filled($this->config('contents.protected') ?? null) &&
            filled($this->config('contents.unprotected') ?? null);
    }

    protected function isConfigured(): bool
    {
        return $this->isConfigured ??
            $this->hasDotEnv() || (filled($this->protectedContents(true)) && filled($this->unprotectedContents(true)));
    }

    protected function setConfigured(): void
    {
        $this->isConfigured = $this->isConfigured();
    }

    protected function setProtected(): void
    {
        $this->protected = $this->protected();
    }

    public function getDomain(string|null $url = null): string|null
    {
        if ($url === null) {
            return request()->getHost();
        }

        return parse_url($url)['host'] ?? null;
    }

    public function setCurrent(TwillRobotsTxtModel $current): static
    {
        $this->current = $current;

        return $this;
    }

    public function allDomainsPublished(): bool
    {
        return false;
    }

    public function getCurrent(): TwillRobotsTxtModel|null
    {
        if (filled($this->current)) {
            return $this->current;
        }

        if (blank($this->current)) {
            $this->current = $this->cacheGet('current-domain');
        }

        if (blank($this->current)) {
            $domains = $this->repository()
                ->orderBy('domain')
                ->get();

            if ($domains->isEmpty()) {
                return null;
            }

            /** @var TwillRobotsTxtModel|null $domain */
            $domain = $domains->firstWhere('domain', $this->getDomain());

            $this->current = $domain;

            $this->cachePut('current-domain', $this->current);
        }

        return $this->current;
    }

    public function robotsTxt(): string
    {
        if ($this->getCurrent() === null) {
            return config('twill-robots-txt.contents.default.protected');
        }

        $result = $this->getCurrent()->published ? $this->getCurrent()->protected : $this->getCurrent()->unprotected;

        return (string) $result;
    }

    public function repository(): TwillRobotsTxtRepository
    {
        return app(TwillRobotsTxtRepository::class);
    }
}
