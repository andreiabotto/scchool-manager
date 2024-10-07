<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    public function all(?array $params): Collection;

    public function find(int $id): ?User;

    public function findWhere(array $params = []): Builder;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
