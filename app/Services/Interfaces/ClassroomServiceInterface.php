<?php

namespace App\Services\Interfaces;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

interface ClassroomServiceInterface
{
    public function getClasses(): Collection;

    public function getClassesById(int $id): Classroom;

    public function createClassroom(array $data): Classroom;

    public function updateClassroom(int $id, array $data): bool;

    public function deleteClassroom(int $id): bool;
}
