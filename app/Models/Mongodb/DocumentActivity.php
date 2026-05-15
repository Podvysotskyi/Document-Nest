<?php

namespace App\Models\Mongodb;

use App\Enums\DocumentActivityType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'document_id',
    'user_id',
    'actor_id',
    'document_title',
    'type',
    'description',
    'metadata',
    'activity_id',
])]
class DocumentActivity extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'document_activities';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => DocumentActivityType::class,
            'metadata' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
