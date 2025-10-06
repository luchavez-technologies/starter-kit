<?php

namespace Luchavez\StarterKit\Traits;

/**
 * Trait UsesProviderPolicyMapTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait UsesProviderPolicyMapTrait
{
    /**
     * Laravel Policy Map
     *
     * @link    https://laravel.com/docs/8.x/authorization#registering-policies
     *
     * @example [ UserPolicy::class => User::class ]
     */
    protected array $policy_map = [];

    public function arePoliciesEnabled(): bool
    {
        return true;
    }

    public function getPolicyMap(): array
    {
        return $this->policy_map;
    }

    public function setPolicyMap(array $policy_map): void
    {
        $this->policy_map = $policy_map;
    }

    /**
     * @return $this
     */
    public function policyMap(array $policy_map): static
    {
        $this->setPolicyMap($policy_map);

        return $this;
    }
}
