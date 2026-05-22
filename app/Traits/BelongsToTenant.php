<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        // Auto-inject tenant_id on create
        static::creating(function ($model) {
            if (session()->has('tenant_id') && empty($model->tenant_id)) {
                $model->tenant_id = session('tenant_id');
            }
        });

        // Auto-scope queries to current tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (session()->has('tenant_id')) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', session('tenant_id'));
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
