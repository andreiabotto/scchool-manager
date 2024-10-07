<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', function () {
            return view('users.list');
        })->name('user.list');

        Route::get('/add', function () {
            return view('users/form');
        });

        Route::get('/edit/{id}', function () {
            return view('users/form');
        });
    });

    Route::prefix('students')->group(function () {
        Route::get('/', function () {
            return view('students.list');
        })->name('students.list');

        Route::get('/add', function () {
            return view('students/form');
        });

        Route::get('/edit/{id}', function () {
            return view('students/form');
        });
    });

    Route::prefix('classrooms')->group(function () {
        Route::get('/', function () {
            return view('classrooms.list');
        })->name('classrooms.list');

        Route::get('/add', function () {
            return view('classrooms/form');
        });

        Route::get('/edit/{id}', function () {
            return view('classrooms/form');
        });
    });

    Route::prefix('registrations')->group(function () {
        Route::get('/', function () {
            return view('student_classroom.list');
        })->name('student_classroom.list');

        Route::get('/add', function () {
            return view('student_classroom/form');
        });

        Route::get('/edit/{id}', function () {
            return view('student_classroom/form');
        });
    });
});

require __DIR__.'/auth.php';
