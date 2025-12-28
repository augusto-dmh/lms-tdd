<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'paddle_product_id' => $this->faker->uuid,
            'slug' => $this->faker->slug,
            'description' => $this->faker->paragraph,
            'tagline' => $this->faker->sentence,
            'image_name' => 'image.png',
            'learnings' => ['Learn 1', 'Learn 2', 'Learn 3'],
        ];
    }

    public function released(?Carbon $date = null): self
    {
        return $this->state(['released_at' => $date ?? Carbon::now()]);
    }
}
