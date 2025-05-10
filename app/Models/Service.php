<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Service extends Model
{
    use BelongsToTenant, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'type',
        'image',
        'is_active',
        'order_no',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order_no' => 'integer',
    ];

    /**
     * Get the tenant that owns the service.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'tenant_id');
    }

    /**
     * Get the translations for the service.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class)->whereIn('service_translations.lang_id', getLanguageIdsByTenant());
    }

    /**
     * Get the translation for a specific language.
     */
    public function getTranslation(int $langId)
    {
        return $this->translations()->where('lang_id', $langId)->first();
    }
}
