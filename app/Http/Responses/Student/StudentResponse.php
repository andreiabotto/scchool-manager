<?php

namespace App\Http\Responses\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResponse extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth->format('d/m/Y'),
            'user_id' =>  $this->user->email,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i:s'),
        ];
    }
}
