<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'=>$this->faker->unique()->safeEmail, 
            'password'=>bcrypt(123456), 
            'role_id'=>2, 
            'email_verified_at'=>$this->faker->dateTime(time()), 
            'remember_token'=>$this->faker->regexify("[A-Za-z0-9]{20}"),
            'created_by'=>$this->faker->numberBetween(0,10), 
            'updated_by'=>$this->faker->numberBetween(0,10), 
            'deleted_by'=>$this->faker->numberBetween(0,10)
        ];
    }
}
