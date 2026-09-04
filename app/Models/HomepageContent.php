<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $fillable = ['content', 'media', 'layout', 'published_content', 'published_media', 'published_layout', 'published_at', 'scheduled_content', 'scheduled_media', 'scheduled_layout', 'scheduled_for', 'updated_by'];

    protected function casts(): array
    {
        return ['content' => 'array', 'media' => 'array', 'layout' => 'array', 'published_content' => 'array', 'published_media' => 'array', 'published_layout' => 'array', 'published_at' => 'datetime', 'scheduled_content' => 'array', 'scheduled_media' => 'array', 'scheduled_layout' => 'array', 'scheduled_for' => 'datetime'];
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

    public static function defaultLayout(): array
    {
        return ['hero' => ['visible' => true, 'order' => 10], 'about' => ['visible' => true, 'order' => 20], 'benefits' => ['visible' => true, 'order' => 30], 'product' => ['visible' => true, 'order' => 40], 'collection' => ['visible' => true, 'order' => 50], 'ritual' => ['visible' => true, 'order' => 60], 'testimonial' => ['visible' => true, 'order' => 70]];
    }

    public function resolvedLayout(bool $published = false): array
    {
        return array_replace_recursive(self::defaultLayout(), ($published ? $this->published_layout : $this->layout) ?? []);
    }

    public function resolvedPublishedContent(): array
    {
        return array_replace_recursive(self::defaults(), $this->published_content ?? []);
    }
}
