<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class Customer extends Authenticatable implements CanResetPasswordContract
{
    use CanResetPassword, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'notes', 'password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    public function getFirstNameAttribute(): string
    {
        return str($this->name)->trim()->before(' ')->toString();
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
