<?php

namespace App\Services;

use App\Models\StudentClassroom;
use App\Repositories\Interfaces\StudentClassroomRepositoryInterface;
use App\Services\Interfaces\StudentClassroomServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class StudentClassroomService implements StudentClassroomServiceInterface
{

    protected $repository;

    public function __construct(StudentClassroomRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(?array $params = []): Collection
    {
        return $this->repository->all($params);
    }

    public function getById(int $id): ?StudentClassroom
    {
        return $this->repository->find($id);
    }

    public function create(array $data): StudentClassroom
    {


        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
