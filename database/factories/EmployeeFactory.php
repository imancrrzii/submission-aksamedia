<?php

namespace Database\Factories;

use App\Models\Division;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $divisions = Division::all();

        $randomDivision = $divisions->random();

        return [
            'id' => (string) Str::uuid(),
            'image' => 'https://picsum.photos/200?random=person',
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'division_id' => $randomDivision->id,
            'position' => $this->faker->jobTitle(),
        ];
    }
}
