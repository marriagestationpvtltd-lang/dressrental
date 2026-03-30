<?php

namespace Tests\Feature;

use App\Models\Dress;
use App\Models\DressCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDressCreateTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeCategory(): DressCategory
    {
        return DressCategory::factory()->create(['is_active' => true]);
    }

    // ── GET /admin/dresses/create ──────────────────────────────────────────

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dresses.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_is_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dresses.create'));

        $response->assertForbidden();
    }

    public function test_admin_can_access_create_page(): void
    {
        $this->makeCategory();

        $response = $this->actingAs($this->makeAdmin())->get(route('admin.dresses.create'));

        $response->assertOk();
        $response->assertViewIs('admin.dresses.create');
        $response->assertViewHas('categories');
        $response->assertViewHas('sizes');
    }

    public function test_admin_can_access_create_page_with_no_categories(): void
    {
        // Verifies the page loads successfully even when no categories exist yet —
        // this is the real-world state on a fresh production deployment.
        $response = $this->actingAs($this->makeAdmin())->get(route('admin.dresses.create'));

        $response->assertOk();
        $response->assertViewIs('admin.dresses.create');
        $response->assertViewHas('categories');
    }

    // ── POST /admin/dresses ────────────────────────────────────────────────

    public function test_admin_can_create_dress_without_images(): void
    {
        $admin    = $this->makeAdmin();
        $category = $this->makeCategory();

        $response = $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'Red Bridal Lehenga',
            'category_id'    => $category->id,
            'size'           => 'M',
            'price_per_day'  => 1200,
            'deposit_amount' => 2000,
            'status'         => 'available',
            'is_featured'    => '1',
            'description'    => 'A beautiful red bridal lehenga.',
            'color'          => 'Red',
            'brand'          => 'Designer',
        ]);

        $response->assertRedirect(route('admin.dresses.index'));

        $this->assertDatabaseHas('dresses', [
            'name'        => 'Red Bridal Lehenga',
            'category_id' => $category->id,
            'size'        => 'M',
            'status'      => 'available',
            'is_featured' => true,
        ]);
    }

    public function test_admin_can_create_dress_with_images(): void
    {
        Storage::fake('public');

        $admin    = $this->makeAdmin();
        $category = $this->makeCategory();

        $response = $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'Blue Saree',
            'category_id'    => $category->id,
            'size'           => 'Free Size',
            'price_per_day'  => 600,
            'deposit_amount' => 0,
            'status'         => 'available',
            'images'         => [
                UploadedFile::fake()->image('dress1.jpg', 400, 400)->size(100),
                UploadedFile::fake()->image('dress2.jpg', 400, 400)->size(100),
            ],
        ]);

        $response->assertRedirect(route('admin.dresses.index'));

        $dress = Dress::where('name', 'Blue Saree')->first();
        $this->assertNotNull($dress);
        $this->assertCount(2, $dress->images);
        $this->assertTrue($dress->images->first()->is_primary);
        $this->assertFalse($dress->images->last()->is_primary);
    }

    public function test_store_fails_without_required_fields(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('admin.dresses.store'), []);

        $response->assertSessionHasErrors(['name', 'category_id', 'size', 'price_per_day', 'deposit_amount', 'status']);
    }

    public function test_store_fails_with_invalid_category(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'Test Dress',
            'category_id'    => 9999,
            'size'           => 'M',
            'price_per_day'  => 100,
            'deposit_amount' => 0,
            'status'         => 'available',
        ]);

        $response->assertSessionHasErrors(['category_id']);
    }

    public function test_store_fails_with_invalid_size(): void
    {
        $admin    = $this->makeAdmin();
        $category = $this->makeCategory();

        $response = $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'Test Dress',
            'category_id'    => $category->id,
            'size'           => 'InvalidSize',
            'price_per_day'  => 100,
            'deposit_amount' => 0,
            'status'         => 'available',
        ]);

        $response->assertSessionHasErrors(['size']);
    }

    public function test_dress_is_not_featured_by_default(): void
    {
        $admin    = $this->makeAdmin();
        $category = $this->makeCategory();

        $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'Basic Dress',
            'category_id'    => $category->id,
            'size'           => 'S',
            'price_per_day'  => 300,
            'deposit_amount' => 0,
            'status'         => 'available',
        ]);

        $this->assertDatabaseHas('dresses', [
            'name'        => 'Basic Dress',
            'is_featured' => false,
        ]);
    }

    public function test_slug_is_generated_on_creation(): void
    {
        $admin    = $this->makeAdmin();
        $category = $this->makeCategory();

        $this->actingAs($admin)->post(route('admin.dresses.store'), [
            'name'           => 'My Unique Dress',
            'category_id'    => $category->id,
            'size'           => 'L',
            'price_per_day'  => 500,
            'deposit_amount' => 0,
            'status'         => 'available',
        ]);

        $dress = Dress::where('name', 'My Unique Dress')->first();
        $this->assertNotNull($dress);
        $this->assertStringContainsString('my-unique-dress', $dress->slug);
    }
}
