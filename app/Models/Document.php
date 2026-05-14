<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'user_id',
    'category_id',
    'title',
    'notes',
    'status',
    'issue_date',
    'expiry_date',
    'original_filename',
    'stored_path',
    'mime_type',
    'file_size',
    'archived_at',
])]
class Document extends Model
{
    use HasFactory, HasUuids;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => DocumentStatus::class,
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'archived_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where($this->qualifyColumn('status'), DocumentStatus::Active);
    }

    public function scopeArchived(Builder $query): void
    {
        $query->where($this->qualifyColumn('status'), DocumentStatus::Archived);
    }

    public function scopeOwnedBy(Builder $query, User $user): void
    {
        $query->where($this->qualifyColumn('user_id'), $user->id);
    }
}
