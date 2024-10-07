<?php

namespace App\Http\Responses\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentClassroomResponse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'classroom_id' => $this->classroom_id
        ];
    }
}
