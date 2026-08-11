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
        return ['brand_name' => 'Luxérie', 'tagline' => 'Belleza, con intención.', 'announcement' => 'Envío de cortesía en México en compras mayores a $900 MXN', 'email' => 'hola@luxerie.mx', 'phone' => '', 'instagram' => '', 'facebook' => '', 'seo_title' => 'Luxérie', 'seo_description' => 'Cuidado consciente para tus rituales diarios.', 'free_shipping_threshold' => 900, 'standard_shipping_cost' => 99];
    }

    public static function resolved(): array
    {
        return array_replace(self::defaults(), self::first()?->values ?? []);
    }
}
