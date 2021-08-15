<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCategoriesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_post_categories()
    {
        $post = Post::factory()->create();
        $category = Category::factory()->create();

        $post->categories()->attach($category);

        $response = $this->getJson(route('api.posts.categories.index', $post));

        $response->assertOk()->assertSee($category->name);
    }

    /**
     * @test
     */
    public function it_can_attach_categories_to_post()
    {
        $post = Post::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson(
            route('api.posts.categories.store', [$post, $category])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $post
                ->categories()
                ->where('categories.id', $category->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_categories_from_post()
    {
        $post = Post::factory()->create();
        $category = Category::factory()->create();

        $response = $this->deleteJson(
            route('api.posts.categories.store', [$post, $category])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $post
                ->categories()
                ->where('categories.id', $category->id)
                ->exists()
        );
    }
}
