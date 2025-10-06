<?php

namespace Luchavez\StarterKit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Luchavez\StarterKit\Scopes\ModelOwnedScope;

/**
 * Trait ModelOwnedTrait
 *
 * @method static static|Builder|\Illuminate\Database\Query\Builder owned(User|bool|null $owner = null)
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait ModelOwnedTrait
{
    /**
     * Boot the owned trait for a model.
     */
    public static function bootModelOwnedTrait(): void
    {
        static::addGlobalScope(new ModelOwnedScope);
    }

    /**
     * Get the name of the "owner id" column.
     */
    public static function getOwnerIdColumn(): string
    {
        return defined('static::OWNER_ID') ? static::OWNER_ID : config('starter-kit.columns.owned.column');
    }

    /**
     * Get the fully qualified "owner id" column.
     */
    public function getQualifiedOwnerIdColumn(): string
    {
        return $this->qualifyColumn($this->getOwnerIdColumn());
    }

    /***** RELATIONSHIPS *****/

    public function owner(): BelongsTo
    {
        return $this->belongsTo(starterKit()->getUserModel(), self::getOwnerIdColumn());
    }
}
