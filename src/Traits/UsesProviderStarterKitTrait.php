<?php

namespace Luchavez\StarterKit\Traits;

use Illuminate\Support\Collection;
use Luchavez\StarterKit\Services\StarterKit;

/**
 * Trait UsesProviderStarterKitTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait UsesProviderStarterKitTrait
{
    use UsesProviderConsoleKernelTrait;
    use UsesProviderDynamicRelationshipsTrait;
    use UsesProviderEnvVarsTrait;
    use UsesProviderHttpKernelTrait;
    use UsesProviderMorphMapTrait;
    use UsesProviderObserverMapTrait;
    use UsesProviderPolicyMapTrait;
    use UsesProviderRepositoryMapTrait;
    use UsesProviderRoutesTrait;

    /**
     * Artisan Commands
     */
    protected array $commands = [];

    public function getExcludedTargetDirectories(): Collection
    {
        return collect()
            ->when(! $this->areConfigsEnabled(), fn ($collection) => $collection->push(StarterKit::CONFIG_DIR))
            ->when(! $this->areMigrationsEnabled(), fn ($collection) => $collection->push(StarterKit::MIGRATIONS_DIR))
            ->when(! $this->areHelpersEnabled(), fn ($collection) => $collection->push(StarterKit::HELPERS_DIR))
            ->when(! $this->areTranslationsEnabled(), fn ($collection) => $collection->push(StarterKit::LANG_DIR))
            ->when(! $this->areRoutesEnabled(), fn ($collection) => $collection->push(StarterKit::ROUTES_DIR))
            ->when(! $this->areRepositoriesEnabled(), fn ($collection) => $collection->push(StarterKit::REPOSITORIES_DIR))
            ->when(! $this->arePoliciesEnabled(), fn ($collection) => $collection->push(StarterKit::POLICIES_DIR))
            ->when(! $this->areObserversEnabled(), fn ($collection) => $collection->push(StarterKit::OBSERVERS_DIR));
    }

    /***** OVERRIDABLE METHODS *****/

    /**
     * Console-specific booting.
     */
    abstract protected function bootForConsole(): void;

    /***** HELPER FILES RELATED *****/

    public function areHelpersEnabled(): bool
    {
        return true;
    }

    /***** TRANSLATIONS RELATED *****/

    public function areTranslationsEnabled(): bool
    {
        return true;
    }

    /***** CONFIGS RELATED *****/

    public function areConfigsEnabled(): bool
    {
        return true;
    }

    /***** MIGRATIONS RELATED *****/

    public function areMigrationsEnabled(): bool
    {
        return true;
    }
}
