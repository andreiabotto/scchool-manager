<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{

    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory(1)->create();

        return [
            'name' => $this->faker->name(),
            'date_of_birth' => $this->faker->date('Y-m-d', 'now'),
            'user_id' => $user[0]->id,
        ];
    }
}
