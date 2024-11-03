<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'list_id' => TodoList::factory(),
            'completed' => false,
            'completed_at' => null,
            'background_color' => null,
            'background_id' => null
        ];
    }

    /**
     * Indicate that the item is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'completed' => true,
                'completed_at' => now()
            ];
        });
    }

    /**
     * Indicate that the item has background color
     */
    public function withBackgroundColor(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'background_color' => $this->faker->hexColor()
            ];
        });
    }

    /**
     * Indicate that the item has background image
     */
    public function withBackgroundImage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'background_id' => $this->faker->numberBetween(1, 10) #TODO create a factory for background images
            ];
        });
    }

    /**
     * Indicate that the item is associated with a TodoList created by a specific user.
     */
    public function forSpecificUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'list_id' => TodoList::factory()->bySpecificUser($user),
            ];
        });
    }

    /**
     * Indicate that the item associated with a specific todo list
     */
    public function forSpecificList(TodoList $list): static
    {
        return $this->state(function (array $attributes) use ($list) {
            return [
                'list_id' => $list->id,
            ];
        });
    }
}
