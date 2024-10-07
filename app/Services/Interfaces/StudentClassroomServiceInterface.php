<?php

namespace App\Services\Interfaces;

use App\Models\StudentClassroom;
use Illuminate\Database\Eloquent\Collection;

interface StudentClassroomServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?StudentClassroom;

    public function create(array $data): StudentClassroom;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
