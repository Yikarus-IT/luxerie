<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'subject_type', 'subject_id', 'description', 'properties', 'created_at'];

    protected function casts(): array
    {
        return ['properties' => 'array', 'created_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, Model|string|null $subject, string $description, array $properties = []): void
    {
        self::create(['user_id' => auth()->id(), 'action' => $action, 'subject_type' => $subject instanceof Model ? $subject::class : $subject, 'subject_id' => $subject instanceof Model ? $subject->getKey() : null, 'description' => $description, 'properties' => $properties, 'created_at' => now()]);
    }
}
