<?php

namespace App\Repositories\Interfaces;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Collection;

interface ClassroomRepositoryInterface
{
    public function all(?array $params): Collection;

    public function find(int $id): ?Classroom;

    public function create(array $data): Classroom;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
