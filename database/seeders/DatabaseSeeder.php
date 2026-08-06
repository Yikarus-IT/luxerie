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

        $face = Category::whereIn('slug', ['face-care', 'cuidado-facial'])->first() ?? new Category;
        $face->fill(['name' => 'Cuidado facial', 'slug' => 'cuidado-facial', 'description' => 'Cremas diarias y cuidado específico para el rostro.', 'is_active' => true])->save();

        $body = Category::whereIn('slug', ['body-care', 'cuidado-corporal'])->first() ?? new Category;
        $body->fill(['name' => 'Cuidado corporal', 'slug' => 'cuidado-corporal', 'description' => 'Cuidado reconfortante más allá del rostro.', 'is_active' => true])->save();

        Product::updateOrCreate(['sku' => 'LUX-HYD-050'], ['category_id' => $face->id, 'name' => 'Crème Lumière', 'slug' => 'creme-lumiere', 'short_description' => 'Una crema diaria envolvente para una hidratación duradera y un acabado suavemente luminoso.', 'description' => "Diseñada como el paso final de todos los días, Crème Lumière reconforta la piel con una textura flexible y sin sensación grasosa.\n\nÚsala por la mañana y por la noche después de limpiar y tratar la piel.", 'price' => 590, 'compare_at_price' => null, 'stock' => 28, 'size_label' => '50 ml', 'is_featured' => true, 'is_active' => true]);
        Product::updateOrCreate(['sku' => 'LUX-NGT-050'], ['category_id' => $face->id, 'name' => 'Crème de Nuit', 'slug' => 'creme-de-nuit', 'short_description' => 'Una crema nocturna más rica, creada para dejar la piel descansada y renovada.', 'description' => 'Una fórmula nocturna aterciopelada para el paso final y restaurador de tu ritual de noche.', 'price' => 640, 'compare_at_price' => 690, 'stock' => 9, 'size_label' => '50 ml', 'is_featured' => true, 'is_active' => true]);
        Product::updateOrCreate(['sku' => 'LUX-HND-075'], ['category_id' => $body->id, 'name' => 'Crème Mains', 'slug' => 'creme-mains', 'short_description' => 'Cuidado para manos de rápida absorción, acabado refinado y confort duradero.', 'description' => 'Mantenla a tu alcance y masajea sobre las manos cada vez que tu piel necesite confort.', 'price' => 320, 'compare_at_price' => null, 'stock' => 4, 'size_label' => '75 ml', 'is_featured' => true, 'is_active' => true]);
    }
}
