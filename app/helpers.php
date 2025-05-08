<?php

function getTenantId(): ?int
{
    return auth()->check() ? (int) auth()->user()->tenant_id : null;
}