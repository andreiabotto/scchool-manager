<?php

namespace App\Repositories\Interfaces;

use App\Models\StudentClassroom;
use Illuminate\Database\Eloquent\Collection;

interface StudentClassroomRepositoryInterface
{
    public function all(?array $params): Collection;

    public function find(int $id): ?StudentClassroom;

    public function create(array $data): StudentClassroom;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
