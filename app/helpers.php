<?php

declare(strict_types=1);

function getTenantId(): ?string
{
    if (tenant()) {
        return (string) tenant('id');
    }

    return auth()->check() ? (string) auth()->user()->tenant_id : null;
}
