<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UpdateItemTest extends TestCase
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
     * Testing if the update item status successfull
     */
    public function test_update_item_status_successfull(): void
    {
        //Arrange
        $item = $this->items->first();
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/' . $item->id,
            [
                'title' => $newTitle,
                'description' => $newDescription,
                'completed' => $newCompleted,
                'background_color' => $newBackgroundColor,
                'list_id' => $item->list->id
            ],
            $this->headers
        );

        //Assert
        $response->assertStatus(200);
    }

    public function test_update_item_when_required_parameter_not_provided(): void
    {
        //Arrange
        $item = $this->items->first();
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/' . $item->id,
            [
                'completed' => $newCompleted,
                'background_color' => $newBackgroundColor
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    public function test_update_item_when_request_body_is_empty(): void
    {
        //Arrange
        $item = $this->items->first();
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/' . $item->id,
            [],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    public function test_update_item_when_list_is_not_is_the_user_list(): void
    {
        //Arrange
        $item = $this->items->first();
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/' . $item->id,
            [
                'title' => $newTitle,
                'description' => $newDescription,
                'completed' => $newCompleted,
                'background_color' => $newBackgroundColor,
                'list_id' => 1000
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }

    public function test_update_item_when_item_id_not_found(): void
    {
        //Arrange
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/1000',
            [
                'title' => $newTitle,
                'description' => $newDescription,
                'completed' => $newCompleted,
                'background_color' => $newBackgroundColor,
                'list_id' => $this->list->id
            ],
            $this->headers
        );

        //Assert
        $response->assertStatus(404);
    }

    public function test_update_item_when_list_id_not_found(): void
    {
        //Arrange
        $item = $this->items->first();
        $newTitle = $this->faker->sentence();
        $newDescription = $this->faker->sentence();
        $newCompleted = $this->faker->boolean();
        $newBackgroundColor = $this->faker->hexColor();

        //Action
        $response = $this->patch(
            '/api/items/' . $item->id,
            [
                'title' => $newTitle,
                'description' => $newDescription,
                'completed' => $newCompleted,
                'background_color' => $newBackgroundColor,
                'list_id' => 1000
            ],
            $this->headersWithMoreData
        );

        //Assert
        $response->assertStatus(422);
    }
}
