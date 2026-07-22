<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@luxerie.test'], ['name' => 'Luxérie Admin', 'password' => 'password', 'is_admin' => true]);

        $face = Category::updateOrCreate(['slug' => 'face-care'], ['name' => 'Face care', 'description' => 'Daily creams and targeted facial care.', 'is_active' => true]);
        $body = Category::updateOrCreate(['slug' => 'body-care'], ['name' => 'Body care', 'description' => 'Comforting care beyond the face.', 'is_active' => true]);

        Product::updateOrCreate(['sku' => 'LUX-HYD-050'], ['category_id' => $face->id, 'name' => 'Crème Lumière', 'slug' => 'creme-lumiere', 'short_description' => 'A cushioning daily cream for lasting hydration and a softly luminous finish.', 'description' => "Designed as an everyday final step, Crème Lumière comforts the skin with a supple, non-greasy texture.\n\nUse morning and evening after cleansing and treatment.", 'price' => 590, 'compare_at_price' => null, 'stock' => 28, 'size_label' => '50 ml', 'is_featured' => true, 'is_active' => true]);
        Product::updateOrCreate(['sku' => 'LUX-NGT-050'], ['category_id' => $face->id, 'name' => 'Crème de Nuit', 'slug' => 'creme-de-nuit', 'short_description' => 'A richer evening cream created to leave skin feeling rested and replenished.', 'description' => 'A velvety nighttime formula for the final, restorative step of your evening ritual.', 'price' => 640, 'compare_at_price' => 690, 'stock' => 9, 'size_label' => '50 ml', 'is_featured' => true, 'is_active' => true]);
        Product::updateOrCreate(['sku' => 'LUX-HND-075'], ['category_id' => $body->id, 'name' => 'Crème Mains', 'slug' => 'creme-mains', 'short_description' => 'Fast-absorbing hand care with a refined finish and lasting comfort.', 'description' => 'Keep within reach and massage into hands whenever skin needs comfort.', 'price' => 320, 'compare_at_price' => null, 'stock' => 4, 'size_label' => '75 ml', 'is_featured' => true, 'is_active' => true]);
    }
}
