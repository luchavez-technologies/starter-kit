<?php

namespace Luchavez\StarterKit\Traits;

/**
 * Trait UsesProviderRoutesTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait UsesProviderRoutesTrait
{
    protected string $append_key = 'append_to_prefix';

    protected ?string $route_prefix = null;

    protected bool $prefix_route_with_file_name = false;

    protected bool $prefix_route_with_directory = false;

    protected array $api_middleware = [];

    protected array $web_middleware = [];

    protected ?array $default_api_middleware = null;

    protected ?array $default_web_middleware = null;

    public function areRoutesEnabled(): bool
    {
        return true;
    }

    /***** PREFIXING *****/

    public function getRoutePrefix(): ?string
    {
        return $this->route_prefix;
    }

    public function setRoutePrefix(?string $route_prefix): void
    {
        $this->route_prefix = $route_prefix;
    }

    /**
     * @return $this
     */
    public function routePrefix(?string $route_prefix): static
    {
        $this->route_prefix = $route_prefix;

        return $this;
    }

    public function shouldPrefixRouteWithFileName(): bool
    {
        return $this->prefix_route_with_file_name;
    }

    /**
     * @return $this
     */
    public function prefixRouteWithFileName(bool $bool = true): static
    {
        $this->prefix_route_with_file_name = $bool;

        return $this;
    }

    public function shouldPrefixRouteWithDirectory(): bool
    {
        return $this->prefix_route_with_directory;
    }

    /**
     * @return $this
     */
    public function prefixRouteWithDirectory(bool $bool = true): static
    {
        $this->prefix_route_with_directory = $bool;

        return $this;
    }

    /***** MIDDLEWARE *****/

    public function getWebMiddleware(): array
    {
        return $this->web_middleware;
    }

    public function setWebMiddleware(array $web_middleware): void
    {
        $this->web_middleware = $web_middleware;
    }

    /**
     * @return $this
     */
    public function webMiddleware(array $web_middleware): static
    {
        $this->web_middleware = $web_middleware;

        return $this;
    }

    public function getApiMiddleware(): array
    {
        return $this->api_middleware;
    }

    public function setApiMiddleware(array $api_middleware): void
    {
        $this->api_middleware = $api_middleware;
    }

    /**
     * @return $this
     */
    public function apiMiddleware(array $api_middleware): static
    {
        $this->api_middleware = $api_middleware;

        return $this;
    }

    public function getDefaultWebMiddleware(): array
    {
        return $this->default_web_middleware ?? starterKit()?->getRouteMiddleware(false) ?? [];
    }

    public function setDefaultWebMiddleware(?array $default_web_middleware): void
    {
        $this->default_web_middleware = $default_web_middleware;
    }

    /**
     * @return $this
     */
    public function defaultWebMiddleware(?array $default_web_middleware): static
    {
        $this->default_web_middleware = $default_web_middleware;

        return $this;
    }

    public function getDefaultApiMiddleware(): array
    {
        return $this->default_api_middleware ?? starterKit()?->getRouteMiddleware(true) ?? [];
    }

    public function setDefaultApiMiddleware(?array $default_api_middleware): void
    {
        $this->default_api_middleware = $default_api_middleware;
    }

    /**
     * @return $this
     */
    public function defaultApiMiddleware(?array $default_api_middleware): static
    {
        $this->default_api_middleware = $default_api_middleware;

        return $this;
    }
}
