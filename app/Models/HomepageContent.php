<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = ['content', 'media', 'updated_by'];

    protected function casts(): array
    {
        return ['content' => 'array', 'media' => 'array'];
    }

    public static function defaults(): array
    {
        return [
            'hero' => ['eyebrow' => 'Crema desmanchadora · 50 g', 'heading' => 'Tu piel más uniforme,', 'highlight' => 'luminosa y cuidada.', 'body' => 'Un ritual diario creado para hidratar, nutrir y acompañar el cuidado de todo tipo de piel.', 'button' => 'Comprar Luxérie Clara'],
            'about' => ['eyebrow' => 'El enfoque Luxérie', 'heading' => 'Una rutina sencilla que convierte el cuidado diario en un momento para ti.', 'body' => 'Su textura cremosa se integra con suavidad a tu ritual de mañana y noche, dejando una sensación hidratada y confortable.'],
            'benefits' => ['eyebrow' => 'Descubre Luxérie Clara', 'heading' => 'Una fórmula, distintas formas de cuidar tu piel.', 'captions' => ['Una apariencia más luminosa', 'Cuidado para un tono uniforme', 'Conoce tu piel', 'Textura que acompaña tu ritual']],
            'product' => ['eyebrow' => 'El esencial de tu tocador', 'heading' => 'Luxérie Clara', 'body' => 'Crema desmanchadora, reafirmante e hidratante para todo tipo de piel.', 'benefits' => ['Ayuda a mejorar la apariencia del tono de la piel.', 'Hidrata y nutre para una sensación más suave.', 'Se integra fácilmente a tu rutina de mañana y noche.']],
            'ritual' => ['eyebrow' => 'Un ritual sencillo', 'heading' => 'Mañana y noche, un momento para ti.', 'note' => 'Realiza una prueba de sensibilidad antes del primer uso.'],
            'testimonial' => ['eyebrow' => 'Historias Luxérie', 'quote' => '“En semanas vi mi piel más clara y pareja.”', 'body' => 'Una experiencia compartida por la comunidad Luxérie Clara.', 'note' => 'Testimonio individual. Los resultados pueden variar.'],
        ];
    }

    public function resolvedContent(): array
    {
        return array_replace_recursive(self::defaults(), $this->content ?? []);
    }
}
