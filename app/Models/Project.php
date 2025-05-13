<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Project extends Model
{
    use BelongsToTenant, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
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
     * Get the tenant that owns the project.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Get the translations for the project.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class)->whereIn('project_translations.lang_id', getLanguageIdsByTenant());
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the translation for a specific language.
     */
    public function getTranslation(int $langId)
    {
        return $this->translations()->where('lang_id', $langId)->first();
    }
}
