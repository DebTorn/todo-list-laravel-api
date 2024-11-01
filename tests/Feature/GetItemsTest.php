<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GetItemsTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $user;
    private $token;
    private $headersWithAuthorization;
    private $headersWithoutAuthorization;
    private $list;
    private $items;

    protected function setUp(): void
    {
        parent::setUp();

        //Create a user and get the token
        $this->user = User::factory()->create();
        $this->token = Auth::login($this->user);

        $this->headersWithoutAuthorization = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        $this->headersWithAuthorization = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer " . $this->token
        ];

        $this->list = TodoList::factory()
            ->bySpecificUser($this->user)
            ->create();

        $this->items = Item::factory()
            ->forSpecificUser($this->user)
            ->forSpecificList($this->list)
            ->count(10)
            ->create();
    }

    /**
     * Testing if the user can get all items status successfull
     */
    public function test_get_all_items_status_successfull(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);

        //Assert
        $response->assertStatus(200);
    }

    /**
     * Testing if the user can get all items status failed
     */
    public function test_get_all_items_list_id_field_required_status(): void
    {
        //Action
        $response = $this->get('/api/items', $this->headersWithAuthorization);

        //Assert
        $response->assertStatus(400);
    }

    /**
     * Testing if the user can get all items status failed
     */
    public function test_get_all_items_list_id_not_found_status(): void
    {
        //Action
        $response = $this->get('/api/items/1000', $this->headersWithAuthorization);

        //Assert
        $response->assertStatus(404);
    }


    /**
     * Testing if the user can get all items status failed
     */
    public function test_get_all_items_item_specific_id_not_found(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id . '/1000', $this->headersWithAuthorization);

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Testing if the user can get all items status failed
     */
    public function test_get_all_items_list_id_not_found(): void
    {
        //Action
        $response = $this->get('/api/items/1000', $this->headersWithAuthorization);

        //Assert
        $response->assertStatus(404);
    }

    /**
     * Testing if the user can get all items json count valid
     */
    public function test_get_all_items_json_count_valid(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);

        //default response json count
        $response->assertJsonCount(2);
    }

    /**
     * Testing if the user can get all items json structure valid
     */
    public function test_get_all_items_json_structure_valid(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);
        //Assert

        //Check the `items` json is array
        $response->assertJsonIsArray('items');

        //Check the structure of the response
        $response->assertJsonStructure([
            'message',
            'items' => [
                '*' => [
                    'id',
                    'list_id',
                    'title',
                    'description',
                    'completed',
                    'completed_at',
                    'background_color',
                    'background_id'
                ]
            ]
        ]);
    }

    /**
     * Testing if the user can get all items json keys valid
     */
    public function test_get_all_items_json_keys_valid(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);
        $responseData = $response->json();
        //Assert

        //Check sturcture by fragments
        foreach ($responseData['items'] as $item) {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('list_id', $item);
            $this->assertArrayHasKey('title', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('completed', $item);
            $this->assertArrayHasKey('completed_at', $item);
            $this->assertArrayHasKey('background_color', $item);
            $this->assertArrayHasKey('background_id', $item);
        }
    }

    /**
     * Testing if the user can get all items response count valid
     */
    public function test_get_all_items_response_count_valid(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);
        //Assert

        //Check the `items` json count
        $response->assertJsonCount(10, 'items');
    }

    /**
     * Testing if the user can get all items successfully
     */
    public function test_get_all_items_values_valid(): void
    {
        //Action
        $response = $this->get('/api/items/' . $this->list->id, $this->headersWithAuthorization);
        //Assert

        //Validate the response `items`
        $response->assertJson([
            'items' => $this->items->toArray()
        ]);
    }
}
