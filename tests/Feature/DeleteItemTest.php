<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeleteItemTest extends TestCase
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

        $this->assertModelExists($this->list);

        $this->items = Item::factory()
            ->forSpecificUser($this->user)
            ->forSpecificList($this->list)
            ->count(10)
            ->create();

        $this->assertDatabaseCount('items', 10);
    }

    /**
     * Testing if the delete item status successfull
     */
    public function test_delete_item_status_successfull(): void
    {

        //Action
        $response = $this->delete(
            '/api/items',
            [
                'list_id' => $this->items[0]->list->id,
                'item_id' => $this->items[0]->id
            ],
            $this->headers
        );

        //Assert
        $response->assertStatus(200);

        $this->assertSoftDeleted($this->items[0]);
    }

    /**
     * Testing delete item with invalid item_id
     */
    public function test_delete_item_with_invalid_item_id(): void
    {
        //Action
        $response = $this->delete(
            '/api/items',
            [
                'list_id' => $this->items[0]->list->id,
                'item_id' => 100
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    /**
     * Testing delete item with invalid list_id
     */
    public function test_delete_item_with_invalid_list_id(): void
    {
        //Action
        $response = $this->delete(
            '/api/items',
            [
                'list_id' => 100,
                'item_id' => $this->items[0]->id
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    /**
     * Testing delete item when item_id is not provided (all items in the list will be deleted)
     */
    public function test_delete_item_when_delete_all_item(): void
    {
        //Action
        $response = $this->delete(
            '/api/items',
            [
                'list_id' => $this->items[0]->list->id
            ],
            $this->headers
        );

        //Assert
        $response->assertStatus(200);

        foreach ($this->items as $item) {
            $this->assertSoftDeleted($item);
        }
    }

    /**
     * Testing delete item when list_id is not provided
     */
    public function test_delete_item_when_list_id_not_provided(): void
    {
        //Action
        $response = $this->delete(
            '/api/items',
            [
                'item_id' => $this->items[0]->id
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    /**
     * Testing delete item when request body is empty
     */
    public function test_delete_item_when_request_body_is_empty(): void
    {
        //Action
        $response = $this->delete(
            '/api/items',
            [],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }
}
