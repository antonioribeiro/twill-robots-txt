<?php

namespace A17\TwillRobotsTxt\Models;

use A17\Twill\Models\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\TwillRobotsTxt\Services\Helpers;
use Illuminate\Database\Eloquent\Relations\HasMany;
use A17\TwillRobotsTxt\Models\Behaviors\Encrypt;
use A17\TwillRobotsTxt\Support\Facades\TwillRobotsTxt as TwillRobotsTxtFacade;

/**
 * @property string|null $domain
 */
class TwillRobotsTxt extends Model
{
    use HasRevisions;
    use Encrypt;

    protected $table = 'twill_robots_txt';

    protected $fillable = ['published', 'domain', 'protected', 'unprotected'];

    protected $appends = ['domain_string', 'status', 'from_dot_env'];

    protected $casts = [
        'published' => 'bool',
    ];

    public function getProtectedAttribute(): string|null
    {
        return $this->decrypt(
            \A17\TwillRobotsTxt\Services\Helpers::instance()
                                                    ->setCurrent($this)
                                                    ->protectedContents(true),
        );
    }

    public function getUnprotectedAttribute(): string|null
    {
        return $this->decrypt(
            \A17\TwillRobotsTxt\Services\Helpers::instance()
                                                    ->setCurrent($this)
                                                    ->unprotectedContents(true),
        );
    }

    public function getPublishedAttribute(): bool
    {
        return (bool) Helpers::instance()
            ->setCurrent($this)
            ->published(true);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany($this->getRevisionModel(), 'twill_robots_txt_id')->orderBy('created_at', 'desc');
    }

    public function getDomainStringAttribute(): string|null
    {
        return $this->domain;
    }

    public function getConfiguredAttribute(): bool
    {
        return filled($this->protected) && filled($this->unprotected);
    }

    public function getStatusAttribute(): string
    {
        if ($this->published && $this->configured) {
            return 'protected';
        }

        return 'unprotected';
    }

    public function getFromDotEnvAttribute(): string
    {
        return TwillRobotsTxtFacade::hasDotEnv() ? 'yes' : 'no';
    }

    public function save(array $options = [])
    {
        TwillRobotsTxtFacade::flushCache();

        return parent::save($options);
    }
}
