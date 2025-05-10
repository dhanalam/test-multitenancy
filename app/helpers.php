<?php

declare(strict_types=1);

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;

function getTenantId(): ?string
{
    if (tenant()) {
        return (string) tenant('id');
    }

    return auth()->check() ? auth()->user()->tenant_id : null;
}

/**
 * Get the tenant IDs from the authenticated user.
 *
 * @return array<int, int>
 */
function getAuthTenantIds(): array
{
    if (!auth()->check()) {
        return [];
    }

    return auth()->tenants()->pluck('id')->toArray();
}

function getLanguageIdsByTenant(): array
{
    if (! tenant()) {
        return [];
    }

    return getAllLanguages()->where('country_id', getTenantId())->pluck('id')->toArray();
}

function getLanguagesByTenant(): Collection
{
    return getAllLanguages()->where('country_id', getTenantId());
}


function getAllLanguages(): Collection
{
    return Language::all();
}

