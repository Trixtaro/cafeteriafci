<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostCommentsTest extends TestCase
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
    public function it_gets_post_comments()
    {
        $post = Post::factory()->create();
        $comments = Comment::factory()
            ->count(2)
            ->create([
                'post_id' => $post->id,
            ]);

        $response = $this->getJson(route('api.posts.comments.index', $post));

        $response->assertOk()->assertSee($comments[0]->content);
    }

    /**
     * @test
     */
    public function it_stores_the_post_comments()
    {
        $post = Post::factory()->create();
        $data = Comment::factory()
            ->make([
                'post_id' => $post->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.posts.comments.store', $post),
            $data
        );

        $this->assertDatabaseHas('comments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $comment = Comment::latest('id')->first();

        $this->assertEquals($post->id, $comment->post_id);
    }
}
