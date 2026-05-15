<?php

namespace App\Models\Sqlite;

use Database\Factories\RoadmapPhaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['label', 'title', 'status', 'sort_order', 'is_visible'])]
class RoadmapPhase extends Model
{
    /** @use HasFactory<RoadmapPhaseFactory> */
    use HasFactory;

    protected $connection = 'sqlite';

    protected static function newFactory(): RoadmapPhaseFactory
    {
        return RoadmapPhaseFactory::new();
    }

    public function items(): HasMany
    {
        return $this->hasMany(RoadmapItem::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }
}
