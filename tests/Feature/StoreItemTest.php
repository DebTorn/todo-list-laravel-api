<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class StoreItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $user;
    private $token;
    private $headers;
    private $headersWithMoreData;
    private $list;
    private $items;

    protected function setUp(): void
    {
        parent::setUp();

        //Create a user and get the token
        $this->user = User::factory()->create();
        $this->token = Auth::login($this->user);

        $this->headers = [
            'Authorization' => "Bearer " . $this->token
        ];

        $this->headersWithMoreData = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $this->token
        ];

        $this->list = TodoList::factory()
            ->bySpecificUser($this->user)
            ->create();
    }

    public function test_store_item_when_required_parameter_not_provided(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence()
        ], $this->headersWithMoreData);

        //Assert
        $response->assertStatus(422);
    }

    public function test_store_item_when_list_is_not_is_the_user_list(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => 1000
        ], $this->headersWithMoreData);

        //Assert
        $response->assertStatus(422);
    }

    /**
     * Testing if the user can store item status successful
     */
    public function test_store_item_status_successful(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => $this->list->id
        ], $this->headers);

        //Assert
        $response->assertStatus(201);
    }

    public function test_store_item_json_count_valid(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => $this->list->id
        ], $this->headers);

        //default response json count
        $response->assertJsonCount(2);
    }

    public function test_store_item_json_structure_valid(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => $this->list->id
        ], $this->headers);

        //Check the structure of the response
        $response->assertJsonStructure([
            'message',
            'item' => [
                'title',
                'description',
                'list_id',
                'id'
            ]
        ]);
    }

    public function test_store_item_json_keys_valid(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => $this->list->id
        ], $this->headers);

        $responseData = $response->json();
        //Assert

        //Check sturcture by fragments
        $this->assertArrayHasKey('title', $responseData['item']);
        $this->assertArrayHasKey('description', $responseData['item']);
        $this->assertArrayHasKey('list_id', $responseData['item']);
        $this->assertArrayHasKey('id', $responseData['item']);
    }

    public function test_store_item_json_values_valid(): void
    {
        //Action
        $response = $this->post('/api/items', [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => $this->list->id
        ], $this->headers);

        $responseData = $response->json();
        //Assert

        //Check sturcture by fragments
        $response->assertJson([
            'item' => $responseData['item']
        ]);
    }
}
