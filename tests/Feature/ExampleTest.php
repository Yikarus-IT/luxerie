<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_guests_are_redirected_from_the_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_an_admin_can_view_the_dashboard(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Buenos días.');
    }

    public function test_published_products_appear_in_the_shop(): void
    {
        $category = Category::create(['name' => 'Rostro', 'slug' => 'rostro', 'is_active' => true]);
        Product::create(['category_id' => $category->id, 'name' => 'Crema de prueba', 'slug' => 'crema-de-prueba', 'sku' => 'TEST-1', 'short_description' => 'Un producto de prueba.', 'price' => 500, 'stock' => 5, 'is_active' => true]);

        $this->get('/shop')->assertOk()->assertSee('Crema de prueba');
    }
}
