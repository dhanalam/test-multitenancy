<?php

declare(strict_types=1);

namespace App\Models;

use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Country extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public $incrementing = false;

    protected $table = 'countries';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public static function getCustomColumns(): array
    {
        return [
            'name',
            'id',
        ];
    }
}
