<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(640,480,'product',true),
            'condition' => $this->faker->numberBetween(1,3),
            'price' => $this->faker->numberBetween(100,10000),
            'brand' => $this->faker->boolean(70) ? $this->faker->word() : null,
        ];
    }
}
