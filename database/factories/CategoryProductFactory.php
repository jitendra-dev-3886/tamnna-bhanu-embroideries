<?php

namespace Database\Factories;

use App\Models\CategoryProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoryProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            ,
            'created_by'=>$this->faker->numberBetween(0,10), 
            'updated_by'=>$this->faker->numberBetween(0,10), 
            'deleted_by'=>$this->faker->numberBetween(0,10)
        ];
    }
}
