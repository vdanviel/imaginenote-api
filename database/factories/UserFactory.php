<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    public function definition()
    {
        //'ip', 'address', 'country', 'local'
        return [
            'ip' => $this->faker->ipv4,
            'address' => $this->faker->address,
            'country' => $this->faker->country,
            'local' => $this->faker->locale
        ];
    }
}
