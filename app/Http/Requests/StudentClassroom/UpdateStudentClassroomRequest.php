<?php

namespace App\Http\Requests\StudentClassroom;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentClassroomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'filled|required|integer|exists:students,id',
            'classroom_id' => 'filled|required|integer|exists:classrooms,id',
        ];
    }

    public function messages()
    {
        return [];
    }
}
