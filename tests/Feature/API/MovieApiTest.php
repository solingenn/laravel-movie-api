<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\User;

class MovieApiTest extends TestCase
{
    /**
     * @return void
     */
    public function test_insert_movie_request(): void
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/movies', [
            'title' => 'A Fistful of Dynamite',
            'release_year' => '1971',
            'description' => 'A former Irish Republican revolutionary...',
        ]);

        $movieId = Movie::where('title', 'A Fistful of Dynamite')->get()[0]['id'];
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $movieId)
                    ->where('data.title', 'A Fistful of Dynamite')
                    ->where('data.release_year', '1971')
                    ->where('data.description', 'A former Irish Republican revolutionary...')
                    ->where('message', 'Movie created.')
            );
    }

    /**
     * @return void
     */
    public function test_get_all_movies_request(): void
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson('/api/movies');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Movies fetched.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_movie_exist_request(): void
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/movies', [
                            'title' => 'A Fistful of Dynamite',
                            'release_year' => '1971',
                            'description' => 'A former Irish Republican revolutionary...',
                        ]);

        $response
            ->assertStatus(409)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Movie title already exists.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_show_movie_request()
    {
        $user = User::factory()->make();

        $movieId = Movie::where('title', 'A Fistful of Dynamite')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson("/api/movies/$movieId");

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $movieId)
                    ->where('data.title', 'A Fistful of Dynamite')
                    ->where('data.release_year', '1971')
                    ->where('data.description', 'A former Irish Republican revolutionary...')
                    ->where('message', 'Movie fetched.')
            );
    }

    /**
     * @return void
     */
    public function test_update_movie_request()
    {
        $user = User::factory()->make();

        $movieId = Movie::where('title', 'A Fistful of Dynamite')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->putJson("/api/movies/$movieId", [
            'title' => 'A Fistful of Dynamite',
            'release_year' => '1971',
            'description' => 'A former Irish Republican revolutionary and a Mexican outlaw...',
        ]);

        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.description', 'A former Irish Republican revolutionary and a Mexican outlaw...')
                    ->where('message', 'Movie updated.')
                    ->etc()
            );
    }

    /**
     * return void
     */
    public function test_delete_movie_request()
    {
        $user = User::factory()->make();

        $movieId = Movie::where('title', 'A Fistful of Dynamite')->get()[0]['id'];
        $movieTitle = Movie::where('title', 'A Fistful of Dynamite')->get()[0]['title'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->deleteJson("/api/movies/$movieId");

        $response
            ->assertStatus(202)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', "Movie $movieTitle deleted.")
                    ->etc()
            );
    }
}
