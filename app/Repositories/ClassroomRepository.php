<?php

namespace App\Repositories;

use App\Models\Classroom;
use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ClassroomRepository implements ClassroomRepositoryInterface
{
    public function all(?array $params = []): Collection
    {
        if ($params) {
            return Classroom::where($params[0], 'LIKE', '%'.$params[1].'%')->orderBy('name')->get();
        }

        return Classroom::all();
    }

    public function find(int $id): ?Classroom
    {
        return Classroom::find($id);
    }

    public function create(array $data): Classroom
    {
        return Classroom::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $classroom = $this->find($id);

        if ($classroom) {
            return $classroom->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $classroom = $this->find($id);

        if ($classroom) {
            return $classroom->delete();
        }

        return false;
    }
}
