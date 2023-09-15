<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mesa_id' => rand(1,10),
            'detalle' => rand(0,1) === 0 ? "Sasarasa" : "Lorem Input",
            // 'detalle' => fake()->paragraph(rand(5,10)),
        ];
    }
}
