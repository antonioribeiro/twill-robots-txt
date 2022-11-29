<?php

namespace A17\TwillRobotsTxt\Support;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use A17\RobotsTxt\Middleware;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use A17\RobotsTxt\RobotsTxt;
use Illuminate\Support\Facades\RateLimiter;
use A17\TwillRobotsTxt\Models\Behaviors\Encrypt;
use A17\TwillRobotsTxt\Repositories\TwillRobotsTxtRepository;
use A17\TwillRobotsTxt\Models\TwillRobotsTxt as TwillRobotsTxtModel;

class TwillRobotsTxt
{
    use Encrypt;

    public const DEFAULT_ERROR_MESSAGE = 'Invisible captcha failed.';

    protected array|null $config = null;

    protected bool|null $isConfigured = null;

    protected bool|null $enabled = null;

    protected TwillRobotsTxtModel|null $current = null;

    public function __construct()
    {
        //        $this->setConfigured();
        //
        //        $this->setEnabled();
        //
        //        $this->configureViews();
    }

    public function config(string|null $key = null, mixed $default = null): mixed
    {
        $this->config ??= filled($this->config) ? $this->config : (array) config('twill-robots-txt');

        if (blank($key)) {
            return $this->config;
        }

        return Arr::get((array) $this->config, $key) ?? $default;
    }

    public function enabled(): bool
    {
        return $this->enabled ?? ($this->hasDotEnv() ? $this->config('enabled') : true);
    }

    public function unprotected(bool $force = false): string|null
    {
        return $this->get('keys.unprotected', 'unprotected', $force);
    }

    public function protected(bool $force = false): string|null
    {
        return $this->get('keys.protected', 'protected', $force);
    }

    public function published(bool $force = false): string|null
    {
        return $this->get('enabled', 'published', $force);
    }

    public function get(string $configKey, string $databaseColumn, bool $force = false): string|null
    {
        if (!$force && (!$this->isConfigured() || !$this->enabled())) {
            return null;
        }

        return $this->hasDotEnv() ? $this->config($configKey) : $this->readFromDatabase($databaseColumn);
    }

    protected function readFromDatabase(string $key): string|bool|null
    {
        if (blank($this->current)) {
            $domains = app(TwillRobotsTxtRepository::class)->orderBy('domain');

            if ($this->hasDotEnv()) {
                $domains->where('domain', '*');
            } else {
                $domains->where('domain', $this->getDomain());
            }

            $domains = $domains->get();

            $domains = $domains->filter(
                fn($domain) => filled($domain->getAttributes()['protected']) &&
                    filled($domain->getAttributes()['unprotected']),
            );

            if ($domains->isEmpty()) {
                return null;
            }

            /** @var TwillRobotsTxtModel|null $domain */
            $domain = $domains->first();

            if ($domain !== null && $domain->domain === '*') {
                $this->current = $domain;
            } else {
                /** @var TwillRobotsTxtModel|null $domain */
                $domain = $domains->firstWhere('domain', $this->getDomain());

                $this->current = $domain;
            }
        }

        if ($this->current === null) {
            return null;
        }

        return $this->decrypt($this->current->getAttributes()[$key]);
    }

    public function hasDotEnv(): bool
    {
        return filled($this->config('keys.protected') ?? null) || filled($this->config('keys.unprotected') ?? null);
    }

    protected function isConfigured(): bool
    {
        return $this->isConfigured ??
            $this->hasDotEnv() || (filled($this->protected(true)) && filled($this->unprotected(true)));
    }

    protected function setConfigured(): void
    {
        $this->isConfigured = $this->isConfigured();
    }

    protected function setEnabled(): void
    {
        $this->enabled = $this->enabled();
    }

    public function getDomain(string|null $url = null): string|null
    {
        $url = parse_url($url ?? request()->url());

        return $url['host'] ?? null;
    }

    public function setCurrent(TwillRobotsTxtModel $current): static
    {
        $this->current = $current;

        return $this;
    }

    public function allDomainsEnabled(): bool
    {
        return $this->hasDotEnv() || $this->readFromDatabase('domain') === '*';
    }

    public function getCurrent()
    {
        if (blank($this->current)) {
            $this->readFromDatabase('domain');
        }

        return $this->current;
    }

    public function robotsTxt(): string
    {
        return $this->getCurrent()->published ? $this->getCurrent()->protected : $this->getCurrent()->unprotected;
    }
}
