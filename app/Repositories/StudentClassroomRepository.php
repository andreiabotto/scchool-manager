<?php

namespace App\Repositories;

use App\Models\StudentClassroom;
use App\Repositories\Interfaces\StudentClassroomRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentClassroomRepository implements StudentClassroomRepositoryInterface
{

    public function all(?array $params = []): Collection
    {
        if ($params) {
            return StudentClassroom::where($params[0], 'LIKE', '%'.$params[1].'%')->orderBy('name')->get();
        }

        return StudentClassroom::all();
    }

    public function find(int $id): ?StudentClassroom
    {
        return StudentClassroom::find($id);
    }

    public function create(array $data): StudentClassroom
    {
        return StudentClassroom::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $student = $this->find($id);

        if ($student) {
            return $student->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $student = $this->find($id);

        if ($student) {
            return $student->delete();
        }

        return false;
    }
}
