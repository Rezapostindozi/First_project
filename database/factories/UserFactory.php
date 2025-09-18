<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(){

        return [
            'username' => $this->faker->unique()->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'country' => $this->faker->randomElement(['iran','indian','england']),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'password' => Hash::make('password'),
            'bio' => $this->faker->text(50),
            'avatar_url' => $this->faker->imageUrl(),
            'is_active' => $this->faker->randomElement([0,1]),
        ];







    }







}
