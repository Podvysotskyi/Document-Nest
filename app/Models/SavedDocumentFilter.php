<?php

namespace App\Models;

use Database\Factories\SavedDocumentFilterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'name',
    'filters',
    'sort',
    'direction',
    'is_default',
])]
class SavedDocumentFilter extends Model
{
    /** @use HasFactory<SavedDocumentFilterFactory> */
    use HasFactory, HasUuids;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_default' => false,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOwnedBy(Builder $query, User $user): void
    {
        $query->where($this->qualifyColumn('user_id'), $user->id);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'is_default' => 'boolean',
        ];
    }
}
