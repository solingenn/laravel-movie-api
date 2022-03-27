<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\User;


class RegisterAndLoginApiTest extends TestCase
{
    public function test_register_api_request()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'noneofyourbussiness',
            'confirm_password' => 'noneofyourbussiness'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'User created successfully.']);
    }

    public function test_login_api_request()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'noneofyourbussiness',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['message' => 'User signed in.']);
    }
    public function test_delete_user()
    {
        $testUser = User::where('name', 'John Doe')->where('email', 'johndoe@gmail.com');
        $testUser->delete();

        $this->assertDeleted($testUser);
    }
}