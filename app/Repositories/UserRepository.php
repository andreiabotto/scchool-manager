<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(?array $params = []): Collection
    {
        if ($params) {
            return User::where($params[0], 'LIKE', '%'.$params[1].'%')->orderBy('name')->get();
        }

        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findWhere(array $params = []): Builder
    {
        return User::where($params[0], 'LIKE', '%'.$params[1].'%');
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $user = $this->find($id);

        if ($user) {
            return $user->update($data);
        }

        return false;
    }

    public function delete(int $id): bool
    {
        $user = $this->find($id);

        if ($user) {
            return $user->delete();
        }

        return false;
    }
}
