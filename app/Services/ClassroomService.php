<?php

namespace App\Services;

use App\Models\Classroom;
use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use App\Services\Interfaces\ClassroomServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ClassroomService implements ClassroomServiceInterface
{
    protected ClassroomRepositoryInterface $classroomRepository;

    public function __construct(ClassroomRepositoryInterface $classroomRepository)
    {
        $this->classroomRepository = $classroomRepository;
    }

    public function getClasses(): Collection
    {
        return $this->classroomRepository->all();
    }

    public function getClassesById(int $id): Classroom
    {
        return $this->classroomRepository->find($id);
    }

    public function createClassroom(array $data): Classroom
    {
        return $this->classroomRepository->create($data);
    }

    public function updateClassroom(int $id, array $data): bool
    {
        return $this->classroomRepository->update($id, $data);
    }

    public function deleteClassroom(int $id): bool
    {
        return $this->classroomRepository->delete($id);
    }
}
