<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
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
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the translations for the task.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TaskTranslation::class)->whereIn('task_translations.lang_id', getLanguageIdsByTenant());
    }

    /**
     * Get the translation for a specific language.
     */
    public function getTranslation(int $langId)
    {
        return $this->translations()->where('lang_id', $langId)->first();
    }
}