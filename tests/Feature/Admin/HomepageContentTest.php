<?php

namespace Tests\Feature\Admin;

use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomepageContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_defaults_before_cms_content_is_saved(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Tu piel más uniforme,');
    }

    public function test_admin_can_update_homepage_copy_and_assign_library_media(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $asset = MediaAsset::create([
            'user_id' => $admin->id,
            'title' => 'Portada',
            'alt_text' => 'Frasco de crema sostenido entre las manos',
            'usage_type' => 'photography',
        ]);
        $asset->addMedia(UploadedFile::fake()->image('portada.jpg', 1200, 1200))->toMediaCollection('image');

        $content = HomepageContent::defaults();
        $content['hero']['heading'] = 'Una portada administrable';

        $this->actingAs($admin)->put(route('admin.homepage.update'), [
            'content' => $content,
            'media' => ['hero' => $asset->id],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('homepage_contents', ['updated_by' => $admin->id]);
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Una portada administrable')
            ->assertSee('portada.jpg');
    }

    public function test_non_admin_cannot_edit_homepage(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.homepage.edit'))->assertForbidden();
    }
}
