<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'slug' => $this->faker->slug,
            'vimeo_id' => $this->faker->uuid,
            'duration_in_min' => $this->faker->numberBetween(1, 99),
        ];
    }
}
