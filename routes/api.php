<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\ProvincesController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\StudentsMedicals;
use App\Http\Controllers\StudentsMedicalsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\UsersController;
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
    Route::post('parent/login', [ApiAuthController::class, 'loginParent'])->name('auth.loginParent');
    Route::post('/register',[ApiAuthController::class, 'register'])->name('register');
    Route::get('generatePassword', [ApiAuthController::class, 'generatePassword'])->name('auth.generatePassword');
    Route::post('updatePassword', [ApiAuthController::class, 'updatePasswordByAdmin'])->name('auth.updatePasswordByAdmin');
    Route::post('sendEmailVerification', [ApiAuthController::class, 'sendEmailVerification'])->name('auth.sendEmailVerification');

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

Route::get('/user/detail/{id}',[UsersController::class, 'show'])->name('user.show');
Route::put('/user/detail/update',[UsersController::class, 'update'])->name('user.update');
Route::put('/user/detail/updatePassword',[UsersController::class, 'updatePassword'])->name('user.updatePassword');

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
Route::get('/grade/detail/{id}',[GradesController::class, 'show'])->name('grade.show');
Route::put('/grade/detail/update',[GradesController::class, 'update'])->name('grade.update');

Route::get('/countries',[CountriesController::class, 'getAllCountries'])->name('getAllCountries');
Route::get('/provinces',[ProvincesController::class, 'getAllProvinces'])->name('getAllProvinces');

Route::post('/parent/detail/save',[ParentsController::class, 'store'])->name('parent.store');
Route::put('/parent/detail/update',[ParentsController::class, 'update'])->name('user.update');
Route::get('/parent/list',[ParentsController::class, 'list'])->name('parent.ist');
Route::get('/parent/detail/{id}',[ParentsController::class, 'show'])->name('parent.show');
Route::post('/parent/detail/assign/students',[ParentsController::class, 'assignStudents'])->name('parent.assignStudents');
Route::get('/parent/childList',[ParentsController::class, 'childList'])->name('parent.childList');

Route::get('/student/medical/list',[StudentsMedicalsController::class, 'list'])->name('studentMedicals.list');
Route::get('/student/medical/detail/{id}',[StudentsMedicalsController::class, 'show'])->name('studentMedicals.show');
Route::post('/student/medical/upload',[StudentsMedicalsController::class, 'upload'])->name('studentMedicals.upload');
Route::post('/student/medical/save',[StudentsMedicalsController::class, 'store'])->name('studentMedicals.store');
