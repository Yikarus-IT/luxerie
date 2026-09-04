<?php

namespace Tests\Feature\Admin;

use App\Models\MediaAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaAssetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_update_and_delete_an_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.media.store'), [
            'title' => 'Crema abierta',
            'alt_text' => 'Tarro abierto de Luxérie Clara sobre fondo crema',
            'usage_type' => 'product',
            'image' => UploadedFile::fake()->image('crema.jpg', 1200, 1200),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $asset = MediaAsset::firstOrFail();
        $this->assertSame('Crema abierta', $asset->title);
        $this->assertCount(1, $asset->getMedia('image'));
        Storage::disk('public')->assertExists($asset->image()->getPathRelativeToRoot());

        $this->actingAs($admin)->put(route('admin.media.update', $asset), [
            'title' => 'Crema abierta actualizada',
            'alt_text' => 'Tarro de crema abierto listo para usarse',
            'usage_type' => 'photography',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('media_assets', [
            'id' => $asset->id,
            'title' => 'Crema abierta actualizada',
            'usage_type' => 'photography',
        ]);

        $this->actingAs($admin)->delete(route('admin.media.destroy', $asset))->assertRedirect();
        $this->assertDatabaseMissing('media_assets', ['id' => $asset->id]);
        $this->assertDatabaseCount('media', 0);
    }

    public function test_non_admin_cannot_open_media_library(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.media.index'))->assertForbidden();
    }
}
