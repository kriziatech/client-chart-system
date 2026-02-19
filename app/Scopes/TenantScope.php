<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::hasUser()) {
            $user = Auth::user();

            // If user has a company_id, scope the query to that company
            if ($user->company_id) {
                $builder->where($model->getTable() . '.company_id', $user->company_id);
            }
        // Optional: If user is Super Admin (and has no company_id? or we want them to see all?), 
        // we might skip this. But existing logic says Super Admin has role type 'super_admin'.
        // For now, let's assume strict scoping. If logic requires Super Admin to see all, 
        // we can add a check like: if ($user->isSuperAdmin()) return;
        }
    }
}