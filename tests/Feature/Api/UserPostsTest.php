<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPostsTest extends TestCase
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
    public function it_gets_user_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()
            ->count(2)
            ->create([
                'author_id' => $user->id,
            ]);

        $response = $this->getJson(route('api.users.posts.index', $user));

        $response->assertOk()->assertSee($posts[0]->title);
    }

    /**
     * @test
     */
    public function it_stores_the_user_posts()
    {
        $user = User::factory()->create();
        $data = Post::factory()
            ->make([
                'author_id' => $user->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.users.posts.store', $user),
            $data
        );

        $this->assertDatabaseHas('posts', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $post = Post::latest('id')->first();

        $this->assertEquals($user->id, $post->author_id);
    }
}
