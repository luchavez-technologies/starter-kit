<?php

namespace Luchavez\StarterKit\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\Pure;

/**
 * Class UnauthorizedException
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @since  2021-11-09
 *
 * @deprecated
 */
class UnauthorizedException extends Exception
{
    #[Pure]
    public function __construct($message = '')
    {
        parent::__construct($message, 403);
    }

    public function render($request): JsonResponse
    {
        return simpleResponse()
            ->data([])
            ->message($this->getMessage() ?: 'You do not have the necessary permission to access this resource.')
            ->failed($this->getCode())
            ->generate();
    }
}
