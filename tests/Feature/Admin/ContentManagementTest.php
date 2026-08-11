<?php

namespace Tests\Feature\Admin;

use App\Models\ContentPage;
use App\Models\HomepageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_open_every_cms_area(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'administrator']);

        foreach (['admin.dashboard', 'admin.homepage.edit', 'admin.media.index', 'admin.products.index', 'admin.categories.index', 'admin.pages.index', 'admin.faqs.index', 'admin.testimonials.index', 'admin.settings.edit', 'admin.users.index', 'admin.activity.index'] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }
    }

    public function test_content_editor_cannot_modify_products(): void
    {
        $editor = User::factory()->create(['is_admin' => false, 'role' => 'content_editor']);

        $this->actingAs($editor)->post(route('admin.products.store'), [])->assertForbidden();
        $this->actingAs($editor)->get(route('admin.homepage.edit'))->assertOk();
    }

    public function test_information_page_can_be_published_to_storefront(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'administrator']);

        $this->actingAs($admin)->post(route('admin.pages.store'), [
            'title' => 'Envíos y devoluciones',
            'body' => 'Información de entrega para nuestros clientes.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.pages.index'));

        $page = ContentPage::firstOrFail();
        $this->get(route('pages.show', $page))->assertOk()->assertSee('Información de entrega');
    }

    public function test_homepage_draft_does_not_replace_live_content_until_published(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'role' => 'administrator']);
        $content = HomepageContent::defaults();
        $content['hero']['heading'] = 'Texto solamente en borrador';

        $this->actingAs($admin)->put(route('admin.homepage.update'), [
            'content' => $content,
            'layout' => HomepageContent::defaultLayout(),
            'action' => 'draft',
        ])->assertSessionHasNoErrors();

        $this->get(route('home'))->assertDontSee('Texto solamente en borrador');
        $this->actingAs($admin)->get(route('admin.homepage.preview'))->assertSee('Texto solamente en borrador');
    }
}
