<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TodoListFactory extends Factory
{

    protected $model = TodoList::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'description' => $this->faker->sentence(),
            'is_completed' => false,
            'completed_at' => null,
            'due_date' => null
        ];
    }

    /**
     * Indicate that the list is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_completed' => true,
                'completed_at' => now()
            ];
        });
    }

    /**
     * Indicate that the list has a due date.
     */
    public function withDueDate(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'due_date' => $this->faker->dateTimeBetween('now', '+1 year')
            ];
        });
    }

    /**
     * Indicate that the list created by specific user.
     */
    public function bySpecificUser(User $user): static
    {
        return $this->state(function (array $attributes) use ($user) {
            return [
                'user_id' => $user->id
            ];
        });
    }

    /**
     * Indicate that the list is associated with a specific category.
     */
    public function forSpecificCategory(Category $category): static
    {
        return $this->state(function (array $attributes) use ($category) {
            return [
                'category_id' => $category->id
            ];
        });
    }
}
