<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'notes'];

    protected function casts(): array
    {
        return [];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', '!=', 'canceled')->sum('total');
    }

    public function getOrderCountAttribute(): int
    {
        return $this->orders()->where('status', '!=', 'canceled')->count();
    }
}
