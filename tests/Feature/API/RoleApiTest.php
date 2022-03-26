<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\Role;
use App\Models\User;

class RoleApiTest extends TestCase
{
    /**
     * @return void
     */
    public function test_insert_role_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/roles', [
                            'role_name' => 'screenwriter'
                        ]);

        $roleId = Role::where('role_name', 'screenwriter')->get()[0]['id'];
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $roleId)
                    ->where('data.role_name', 'screenwriter')
                    ->where('message', 'Role created.')
            );
    }

    /**
     * @return void
     */
    public function test_get_all_roles_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson('/api/roles');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Roles fetched.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_role_exist_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/roles', [
                            'role_name' => 'screenwriter'
                        ]);

        $response
            ->assertStatus(409)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Role already exists.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_show_role_request()
    {
        $user = User::factory()->make();
        
        $roleId = Role::where('role_name', 'screenwriter')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson("/api/roles/$roleId");

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $roleId)
                    ->where('data.role_name', 'screenwriter')
                    ->where('message', 'Role fetched.')
            );
    }

    /**
     * @return void
     */
    public function test_update_person_request()
    {
        $user = User::factory()->make();
        
        $roleId = Role::where('role_name', 'screenwriter')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->putJson("/api/roles/$roleId", [
                            'role_name' => 'screenplay'
                        ]);
             
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.role_name', 'screenplay')
                    ->where('message', 'Role updated.')
                    ->etc()
            );
    }

    /**
     * return void
     */
    public function test_delete_role_request()
    {
        $user = User::factory()->make();
        
        $roleId = Role::where('role_name', 'screenplay')->get()[0]['id'];
        $roleName = Role::where('id', $roleId)->get()[0]['role_name'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->deleteJson("/api/roles/$roleId");

        $response
            ->assertStatus(202)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', "Role $roleName deleted.")
                    ->etc()
            );
    }
}
