<?php

function getTenantId(): ?int
{
    if (tenant()) {
        return (int) tenant('id');
    }

    return auth()->check() ? (int) auth()->user()->tenant_id : null;
}