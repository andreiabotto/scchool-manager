<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentRepository implements StudentRepositoryInterface
{

    public function all(?array $params = []): Collection
    {
        if ($params) {
            return Student::where($params[0], 'LIKE', '%'.$params[1].'%')->orderBy('name')->get();
        }

        return Student::all();
    }

    public function find(int $id): ?Student
    {
        return Student::find($id);
    }

    public function create(array $data): Student
    {
        return Student::create($data);
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
