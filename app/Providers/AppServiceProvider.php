<?php

namespace App\Providers;

use App\Repositories\ClassroomRepository;
use App\Repositories\Interfaces\ClassroomRepositoryInterface;
use App\Repositories\Interfaces\StudentClassroomRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\StudentClassroomRepository;
use App\Repositories\StudentRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\ClassroomService;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\Interfaces\ClassroomServiceInterface;
use App\Services\Interfaces\StudentClassroomServiceInterface;
use App\Services\Interfaces\StudentServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\StudentClassroomService;
use App\Services\StudentService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(ClassroomRepositoryInterface::class, ClassroomRepository::class);
        $this->app->bind(ClassroomServiceInterface::class, ClassroomService::class);

        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);

        $this->app->bind(StudentClassroomServiceInterface::class, StudentClassroomService::class);
        $this->app->bind(StudentClassroomRepositoryInterface::class, StudentClassroomRepository::class);

        $this->app->bind(AuthServiceInterface::class, AuthService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
