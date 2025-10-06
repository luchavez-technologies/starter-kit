<?php

namespace Luchavez\StarterKit\Traits;

use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Cache\CacheManager;
use RuntimeException;
use Throwable;

/**
 * Trait HasTaggableCacheTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait HasTaggableCacheTrait
{
    abstract public function getMainTag(): string;

    protected ?CacheManager $cache_manager = null;

    public function getCacheManager(): CacheManager
    {
        return $this->cache_manager ?? cache();
    }

    public function setCacheManager(?CacheManager $cache_manager): void
    {
        $this->cache_manager = $cache_manager;
    }

    public function isCacheTaggable(): bool
    {
        try {
            return method_exists($this->getCacheManager()->getStore(), 'tags');
        } catch (Throwable) {
            return false;
        }
    }

    public function clearCache(): bool
    {
        $manager = $this->getCacheManager();

        if ($this->isCacheTaggable()) {
            $manager = $manager->tags($this->getMainTag());
        }

        return $manager->flush();
    }

    protected function getTags(...$tags): array
    {
        return collect($this->getMainTag())->merge($tags)->unique()->filter()->toArray();
    }

    /**
     * @param  string[]  $tags
     */
    protected function getCache(array $tags, string $key, Closure $closure, bool $rehydrate = false, Closure|DateTimeInterface|DateInterval|int|null $ttl = null): mixed
    {
        $tags = $this->getTags(...$tags);

        $manager = $this->getCacheManager();

        $this->prepareCacheManagerAndKey(manager: $manager, key: $key, tags: $tags);

        if ($rehydrate) {
            $manager->forget($key);
        }

        // Copied and improved from \Illuminate\Cache\Repository's remember() function
        $value = $manager->get($key);

        if (! is_null($value)) {
            return $value;
        }

        // Pass reference to $ttl to provide option to override cache expiration
        $value = $closure($ttl);

        $ttl = value($ttl);

        if ($ttl !== 0) {
            $manager->put($key, $value, $ttl);
        }

        return $value;
    }

    /**
     * @param  string[]  $tags
     */
    public function forgetCache(array $tags, string $key): bool
    {
        $tags = $this->getTags(...$tags);

        $manager = $this->getCacheManager();

        $this->prepareCacheManagerAndKey(manager: $manager, key: $key, tags: $tags);

        return $manager->forget($key);
    }

    protected function prepareCacheManagerAndKey(CacheManager &$manager, string &$key, array $tags = []): void
    {
        if ($this->isCacheTaggable()) {
            $manager = $manager->tags($tags);
        } else {
            $key = collect(['tags' => $tags, 'key' => $key])->toJson();
        }
    }

    public function validateClass(string &$class, string $base_class): void
    {
        $object = new $class;

        $class = get_class($object);

        if (! is_subclass_of($object, $base_class)) {
            throw new RuntimeException('Invalid '.$base_class.' class: '.$class);
        }
    }
}
