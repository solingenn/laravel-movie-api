<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Person;
use App\Models\User;

class PersonApiTest extends TestCase
{
    /**
     * @return void
     */
    public function test_insert_person_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/persons', [
                            'first_name' => 'James',
                            'last_name' => 'Coburn',
                            'born' => '1928-08-18',
                        ]);

        $personId = Person::where('first_name', 'James')->where('last_name', 'Coburn')->get()[0]['id'];
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $personId)
                    ->where('data.first_name', 'James')
                    ->where('data.last_name', 'Coburn')
                    ->where('data.born', '1928-08-18')
                    ->where('message', 'Person created.')
            );
    }

    /**
     * @return void
     */
    public function test_get_all_person_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson('/api/persons');

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', 'Persons fetched.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_person_exist_request(): void
    {
        $user = User::factory()->make();
        
        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->postJson('/api/persons', [
                            'first_name' => 'James',
                            'last_name' => 'Coburn',
                            'born' => '1928-08-18',
                        ]);

        $response
            ->assertStatus(409)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', false)
                    ->where('message', 'Person already exists.')
                    ->etc()
            );
    }

    /**
     * @return void
     */
    public function test_show_person_request()
    {
        $user = User::factory()->make();
        
        $personId = Person::where('first_name', 'James')->where('last_name', 'Coburn')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->getJson("/api/persons/$personId");

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.id', $personId)
                    ->where('data.first_name', 'James')
                    ->where('data.last_name', 'Coburn')
                    ->where('data.born', '1928-08-18')
                    ->where('message', 'Person fetched.')
            );
    }

    /**
     * @return void
     */
    public function test_update_person_request()
    {
        $user = User::factory()->make();
        
        $personId = Person::where('first_name', 'James')->where('last_name', 'Coburn')->get()[0]['id'];

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->putJson("/api/persons/$personId", [
                            'first_name' => 'James',
                            'last_name' => 'Coburn',
                            'born' => '1928-08-20',
                        ]);
             
        $response
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('data.born', '1928-08-20')
                    ->where('message', 'Person updated.')
                    ->etc()
            );
    }

    /**
     * return void
     */
    public function test_delete_person_request()
    {
        $user = User::factory()->make();
        
        $personId = Person::where('first_name', 'James')->where('last_name', 'Coburn')->get()[0]['id'];
        $personFirstName = Person::where('id', $personId)->get()[0]['first_name'];
        $personLastName = Person::where('id', $personId)->get()[0]['last_name'];
        $personName = "$personFirstName $personLastName";

        $response = $this->actingAs($user)
                        ->withSession(['banned' => false])
                        ->deleteJson("/api/persons/$personId");

        $response
            ->assertStatus(202)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('success', true)
                    ->where('message', "Person $personName deleted.")
                    ->etc()
            );
    }
}
