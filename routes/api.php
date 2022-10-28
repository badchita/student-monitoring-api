<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\ProvincesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('teacher/login', [ApiAuthController::class, 'loginTeacher'])->name('auth.loginTeacher');
    Route::post('admin/login', [ApiAuthController::class, 'loginAdmin'])->name('auth.loginAdmin');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout.api');
    });
});

Route::group(['middleware' => ['is_verify_email']], function () {
});
Route::get('account/verify/{token}', [ApiAuthController::class, 'verifyAccount'])->name('user.verify');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/teacher/detail/save',[TeachersController::class, 'store'])->name('teacher.store');
Route::put('/teacher/detail/update',[TeachersController::class, 'update'])->name('teacher.update');
Route::get('/teacher/detail/{id}',[TeachersController::class, 'show'])->name('teacher.show');
Route::get('/teacher/list',[TeachersController::class, 'list'])->name('teacher.ist');
Route::get('/teacher/generateIdNumber',[TeachersController::class, 'generateIdNumber'])->name('teacher.generateIdNumber');

Route::post('/subject/detail/save',[SubjectsController::class, 'store'])->name('subject.store');
Route::put('/subject/detail/update',[SubjectsController::class, 'update'])->name('subject.update');
Route::get('/subject/detail/{id}',[SubjectsController::class, 'show'])->name('subject.show');
Route::put('/subject/detail/assign/teacher',[SubjectsController::class, 'assignTeacher'])->name('subject.assignTeacher');
Route::post('/subject/detail/assign/students',[SubjectsController::class, 'assignStudents'])->name('student.assignStudents');
Route::get('/subject/list',[SubjectsController::class, 'list'])->name('subject.list');

Route::post('/student/detail/save',[StudentsController::class, 'store'])->name('student.store');
Route::get('/student/detail/{id}',[StudentsController::class, 'show'])->name('student.show');
Route::put('/student/detail/update',[StudentsController::class, 'update'])->name('student.update');
Route::get('/student/list',[StudentsController::class, 'list'])->name('student.ist');
Route::get('/student/generateIdNumber',[StudentsController::class, 'generateIdNumber'])->name('student.generateIdNumber');

Route::get('/grade/list',[GradesController::class, 'list'])->name('grade.list');

Route::get('/countries',[CountriesController::class, 'getAllCountries'])->name('getAllCountries');
Route::get('/provinces',[ProvincesController::class, 'getAllProvinces'])->name('getAllProvinces');
