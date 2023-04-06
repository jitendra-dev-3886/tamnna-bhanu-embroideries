<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>\Illuminate\Support\Str::substr($this->faker->name,0,191), 
            'guard_name'=>"root", 
            'label'=>\Illuminate\Support\Str::substr($this->faker->name,0,191),
            'created_by'=>$this->faker->numberBetween(0,10), 
            'updated_by'=>$this->faker->numberBetween(0,10), 
            'deleted_by'=>$this->faker->numberBetween(0,10)
        ];
    }
}
