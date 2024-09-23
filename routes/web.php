<?php

use App\Charts\teacherDashboardChart;
use App\Http\Controllers\assignmentController;
use App\Http\Controllers\attendanceController;
use App\Http\Controllers\batchController;
use App\Http\Controllers\courseController;
use App\Http\Controllers\marksController;
use App\Http\Controllers\staffController;
use App\Http\Controllers\teacherDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::prefix('/dashboard/student')->group(function () {
    Route::view('/home/{id}', 'student.dashboard')->name('student-dashboard');

    Route::view('/assignments', 'student.pages.assignments')->name('student-assignments');

    Route::view('/attendance/{id}', 'student.pages.attendance')->name('student-attendance');

    Route::view('/courses/{id}', 'student.pages.courses')->name('student-courses');

    Route::view('/settings/{id}', 'student.pages.settings')->name('student-settings');

    Route::view('/marks/{id}', 'student.pages.marks')->name('student-marks');

    Route::get('/attendance/{id}', [attendanceController::class, 'makeCharts'])->name('student-attendance');

    Route::get('/courses/{id}', [courseController::class, 'makeCharts'])->name('student-courses');

    Route::get('/assignments-get/{batch}', [assignmentController::class, 'getAssignments'])->name('student-assignments-get');

    Route::post('/upload-assignment', [assignmentController::class, 'uploadAssignmentStudent'])->name('student-upload-assignments');

    Route::get('/get-status', [assignmentController::class, 'getAssignmentStatus'])->name('get-assignment-status');

    Route::get('/get-marks', [marksController::class, 'getMarks'])->name('get-student-marks');
});

Route::prefix('/dashboard/teacher')->group(function () {
    Route::view('/home/{id}', 'teacher.pages.dashboard')->name('teacher-dashboard');

    Route::view('/attendance/mark', 'teacher.pages.attendance')->name('teacher-attendance');

    Route::view('/attendance/view/', 'teacher.pages.view-attendance')->name('teacher-view-attendance');

    Route::view('/assignments/', 'teacher.pages.attendance')->name('teacher-assignments');

    Route::view('/assignments/view', 'teacher.pages.view-assignments')->name('teacher-view-assignments');

    Route::view('/assignments/upload', 'teacher.pages.upload-assignment')->name('teacher-upload-assignments');


    Route::view('/settings/{id}', 'teacher.pages.attendance')->name('teacher-settings');


    Route::get('/home/{id}', [teacherDashboardController::class, 'makeCharts'])->name('teacher-dashboard');

    Route::get('/attendance/view', [attendanceController::class, 'makeCharts2'])->name('teacher-view-attendance');

    Route::get('/assignments/view', [assignmentController::class, 'makeCharts'])->name('teacher-view-assignments');

    Route::post('/upload-assignment/', [assignmentController::class, 'uploadAssignment'])->name('upload-assignment');

    Route::get('/assignment-count/', [assignmentController::class, 'countAssignment'])->name('assignment-count');

    Route::get('/submitted-assignment/', [assignmentController::class, 'getSubmittedAssignments'])->name('submitted-assignment');

    Route::post('/mark-assignment/', [marksController::class, 'markAssignment'])->name('mark-assignment');
});




Route::prefix("/dashboard/staff/")->group(function () {
    Route::view('/home/{id}', 'staff.pages.dashboard')->name('staff-dashboard');

    Route::view('/courses/view-courses/{id}', 'staff.pages.view-courses')->name('staff-view-courses');

    Route::view('/courses/add-courses/{id}', 'staff.pages.add-courses')->name('staff-add-courses');

    Route::view('/teachers/add-teachers/{id}', 'staff.pages.add-teachers')->name('staff-add-teachers');

    Route::view('/teachers/view-teachers/{id}', 'staff.pages.view-teachers')->name('staff-view-teachers');

    Route::view('/batches/add-batches', 'staff.pages.add-batches')->name('staff-add-batches');

    Route::view('/batches/view-batches/', 'staff.pages.view-batches')->name('staff-view-batches');

    Route::view('/batches/assign-batches/{id}', 'staff.pages.assign-batches')->name('staff-assign-batches');




    Route::post('/add-course-data', [courseController::class, 'addCourse'])->name('add-courses-data');

    Route::get('/get-course-data', [courseController::class, 'getCourses'])->name('get-courses-data');

    Route::post('/add-instructor', [staffController::class, 'addInstructor'])->name('staff.addInstructor');

    Route::get('/get-courses', [batchController::class, 'getCourses']);

    Route::post('/get-teachers', [batchController::class, 'getTeachers']);

    Route::post('/add-batch', [batchController::class, 'addBatch']);


    Route::get('/batches/view-batches', [batchController::class, 'getBatches'])->name('staff-view-batches');


    Route::post('/update-batch/{id}', [BatchController::class, 'updateBatch'])->name('batches.update');

    Route::delete('/delete-batch/{id}', [BatchController::class, 'deleteBatch'])->name('batches.delete');

    Route::get('/batches/{id}/edit', [BatchController::class, 'editBatch']);

    Route::post('/get-teachers-and-duration', [BatchController::class, 'getTeachersAndDuration']);
});
