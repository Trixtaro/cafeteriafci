<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryPostsTest extends TestCase
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
    public function it_gets_category_posts()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create();

        $category->posts()->attach($post);

        $response = $this->getJson(
            route('api.categories.posts.index', $category)
        );

        $response->assertOk()->assertSee($post->title);
    }

    /**
     * @test
     */
    public function it_can_attach_posts_to_category()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create();

        $response = $this->postJson(
            route('api.categories.posts.store', [$category, $post])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $category
                ->posts()
                ->where('posts.id', $post->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_posts_from_category()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create();

        $response = $this->deleteJson(
            route('api.categories.posts.store', [$category, $post])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $category
                ->posts()
                ->where('posts.id', $post->id)
                ->exists()
        );
    }
}
