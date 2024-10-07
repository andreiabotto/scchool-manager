<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentClassroom>
 */
class StudentClassroomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $student = Student::factory(1)->create();
        $classroom = Classroom::factory(1)->create();

        return [
            'student_id' => $student[0]->id,
            'classroom_id' => $classroom[0]->id,
        ];
    }
}
