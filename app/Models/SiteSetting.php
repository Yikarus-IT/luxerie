<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['values', 'updated_by'];

    protected function casts(): array
    {
        return ['values' => 'array'];
    }

    public static function defaults(): array
    {
        return ['brand_name' => 'Luxérie', 'tagline' => 'Belleza, con intención.', 'announcement' => 'No hay devolución por ser un producto higiénico', 'email' => 'sabinorendon1959@gmail.com', 'phone' => '', 'instagram' => '', 'facebook' => '', 'seo_title' => 'Luxérie', 'seo_description' => 'Cuidado consciente para tus rituales diarios.', 'standard_shipping_cost' => 99];
    }

    public static function resolved(): array
    {
        return array_replace(self::defaults(), self::first()?->values ?? []);
    }
}
