<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomepageRevision extends Model
{
    public $timestamps = false;

    protected $fillable = ['content', 'media', 'layout', 'user_id', 'created_at'];

    protected function casts(): array
    {
        return ['content' => 'array', 'media' => 'array', 'layout' => 'array', 'created_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
