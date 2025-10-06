<?php

namespace Luchavez\StarterKit\Traits;

/**
 * Trait UsesProviderEnvVarsTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait UsesProviderEnvVarsTrait
{
    /**
     * Publishable Environment Variables
     *
     * @link    https://laravel.com/docs/8.x/eloquent#observers
     *
     * @example [ 'SK_OVERRIDE_EXCEPTION_HANDLER' => true ]
     */
    protected array $env_vars = [];

    public function getEnvVars(): array
    {
        return $this->env_vars;
    }
}
