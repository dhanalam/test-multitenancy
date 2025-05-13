<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTranslation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'lang_id',
        'name',
        'description',
    ];

    /**
     * Get the task that owns the translation.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the language that owns the translation.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
