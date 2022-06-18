<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state=
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = $this->faker->sentence(10);
        $content = $this->faker->text(10);

        return [
            'user_id' => User::get()->random()->id,
            'title' => $title,
            'description'=> $content
        ];
    }
}
