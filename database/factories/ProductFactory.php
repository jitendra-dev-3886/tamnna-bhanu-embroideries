<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->firstName, 
            'description'=>$this->faker->sentence,
            'created_by'=>$this->faker->numberBetween(0,10), 
            'updated_by'=>$this->faker->numberBetween(0,10), 
            'deleted_by'=>$this->faker->numberBetween(0,10)
        ];
    }
}
