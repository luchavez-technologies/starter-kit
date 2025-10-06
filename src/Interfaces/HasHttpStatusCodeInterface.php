<?php

namespace Luchavez\StarterKit\Interfaces;

/**
 * Interface UsesHttpStatusCodeInterface
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
interface HasHttpStatusCodeInterface
{
    /**
     * Determine if the request was successful.
     */
    public function successful(): bool;

    /**
     * Determine if the response code was "OK".
     */
    public function ok(): bool;

    /**
     * Determine if the response was a 401 "Unauthorized" response.
     */
    public function unauthorized(): bool;

    /**
     * Determine if the response was a 403 "Forbidden" response.
     */
    public function forbidden(): bool;

    /**
     * Determine if the response indicates a client or server error occurred.
     */
    public function failed(): bool;

    /**
     * Determine if the response indicates a client error occurred.
     */
    public function clientError(): bool;

    /**
     * Determine if the response indicates a server error occurred.
     */
    public function serverError(): bool;
}
