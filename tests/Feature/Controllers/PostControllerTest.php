<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Post;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_posts()
    {
        $posts = Post::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('posts.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.posts.index')
            ->assertViewHas('posts');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_post()
    {
        $response = $this->get(route('posts.create'));

        $response->assertOk()->assertViewIs('app.posts.create');
    }

    /**
     * @test
     */
    public function it_stores_the_post()
    {
        $data = Post::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('posts.store'), $data);

        $this->assertDatabaseHas('posts', $data);

        $post = Post::latest('id')->first();

        $response->assertRedirect(route('posts.edit', $post));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_post()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response
            ->assertOk()
            ->assertViewIs('app.posts.show')
            ->assertViewHas('post');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_post()
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.edit', $post));

        $response
            ->assertOk()
            ->assertViewIs('app.posts.edit')
            ->assertViewHas('post');
    }

    /**
     * @test
     */
    public function it_updates_the_post()
    {
        $post = Post::factory()->create();

        $user = User::factory()->create();

        $data = [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->text,
            'author_id' => $user->id,
        ];

        $response = $this->put(route('posts.update', $post), $data);

        $data['id'] = $post->id;

        $this->assertDatabaseHas('posts', $data);

        $response->assertRedirect(route('posts.edit', $post));
    }

    /**
     * @test
     */
    public function it_deletes_the_post()
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));

        $this->assertSoftDeleted($post);
    }
}
