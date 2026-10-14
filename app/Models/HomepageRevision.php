<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageRevision extends Model
{
    protected $fillable = ['content', 'media', 'layout', 'user_id'];

    protected $casts = [
        'content' => 'array',
        'media' => 'array',
        'layout' => 'array',
    ];

    public const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
