<?php

namespace App\Http\Requests\StudentClassroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentClassroomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'classroom_id' => 'required|integer|exists:classrooms,id',
        ];
    }

    public function messages()
    {
        return [];
    }
}
