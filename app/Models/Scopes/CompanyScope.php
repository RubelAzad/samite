<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    /**
     * Resolving the authenticated user queries the users table, which re-applies this
     * scope. Without this guard that re-entrant call resolves the user again, forever.
     */
    private static bool $resolvingUser = false;

    public function apply(Builder $builder, Model $model): void
    {
        if (self::$resolvingUser) {
            return;
        }

        self::$resolvingUser = true;
        try {
            if (! Auth::check()) {
                return;
            }

            $user = Auth::user();
        } finally {
            self::$resolvingUser = false;
        }

        if (! $user || $user->isSuperAdmin() || ! $user->company_id) {
            return;
        }

        $builder->where($model->getTable().'.company_id', $user->company_id);
    }
}
