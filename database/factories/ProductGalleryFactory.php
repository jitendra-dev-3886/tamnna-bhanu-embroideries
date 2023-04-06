<?php

namespace Database\Factories;

use App\Models\ProductGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductGalleryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductGallery::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'gallery_original'=>, 
            'gallery_thumbnail'=>,
            'created_by'=>$this->faker->numberBetween(0,10), 
            'updated_by'=>$this->faker->numberBetween(0,10), 
            'deleted_by'=>$this->faker->numberBetween(0,10)
        ];
    }
}
