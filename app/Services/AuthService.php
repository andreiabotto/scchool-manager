<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUser(array $params): ?User
    {
        $user =  $this->userRepository->findWhere(['email', $params['email']])->first();

        if (!$user || !Hash::check($params['password'], $user->password)) {
            return null;
        }

        return $user;
    }

}
