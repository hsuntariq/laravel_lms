<?php

use App\Charts\teacherDashboardChart;
use App\Http\Controllers\assignmentController;
use App\Http\Controllers\attendanceController;
use App\Http\Controllers\batchController;
use App\Http\Controllers\courseController;
use App\Http\Controllers\marksController;
use App\Http\Controllers\staffController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\teacherController;
use App\Http\Controllers\teacherDashboardController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');



Route::prefix('/dashboard/student')->middleware(['auth', 'student'])->group(function () {
    Route::view('/home/{id}', 'student.dashboard')->name('student-dashboard');

    Route::view('/assignments/{id}', 'student.pages.assignments')->name('student-assignments');

    Route::view('/attendance/{id}', 'student.pages.attendance')->name('student-attendance');

    Route::view('/courses/{id}', 'student.pages.courses')->name('student-courses');

    Route::view('/settings/{id}', 'student.pages.settings')->name('student-settings');

    Route::view('/marks/{id}', 'student.pages.marks')->name('student-marks');

    Route::get('/attendance/{id}', [attendanceController::class, 'makeCharts'])->name('student-attendance');

    Route::get('/courses/{id}', [courseController::class, 'makeCharts'])->name('student-courses');

    Route::get('/assignments-get/{id}/', [assignmentController::class, 'getAssignments'])->name('student-assignments-get');

    Route::post('/upload-assignment/{id}', [assignmentController::class, 'uploadAssignmentStudent'])->name('student-upload-assignments');

    Route::get('/get-status', [assignmentController::class, 'getAssignmentStatus'])->name('get-assignment-status');

    Route::get('/get-marks/{id}', [marksController::class, 'getMarks'])->name('get-student-marks');

    Route::get('/get-total-classes/{id}', [attendanceController::class, 'totalClasses'])->name('totalClasses');

    Route::get('get-data-count/{id}', [studentController::class, 'countData'])->name('count-stuudent-data');

    Route::get('get-attendance-record/{id}', [attendanceController::class, 'getStudentAttendace'])->name('get-attendace-record');
});

Route::prefix('/dashboard/teacher')->middleware(['auth', 'teacher'])->group(function () {
    Route::view('/home/{id}', 'teacher.pages.dashboard')->name('teacher-dashboard');

    Route::view('/attendance/mark/{id}', 'teacher.pages.attendance')->name('teacher-attendance');

    Route::view('/attendance/view/{id}', 'teacher.pages.view-attendance')->name('teacher-view-attendance');

    Route::view('/assignments/{id}', 'teacher.pages.view-assignments')->name('teacher-assignments');

    Route::view('/assignments/view/{id}', 'teacher.pages.view-assignments')->name('teacher-view-assignments');

    Route::view('/assignments/upload/{id}', 'teacher.pages.upload-assignment')->name('teacher-upload-assignments');


    Route::view('/settings/{id}', 'teacher.pages.attendance')->name('teacher-settings');


    Route::get('/home/{id}', [teacherDashboardController::class, 'makeCharts'])->name('teacher-dashboard');

    Route::get('/attendance/data/{id}', [attendanceController::class, 'makeCharts2'])->name('teacher-view-attendance2');

    Route::get('/assignments/view/{id}', [assignmentController::class, 'makeCharts'])->name('teacher-view-assignments');

    Route::post('/upload-assignment/', [assignmentController::class, 'uploadAssignment'])->name('upload-assignment');

    Route::get('/assignment-count/', [assignmentController::class, 'countAssignment'])->name('assignment-count');

    Route::get('/submitted-assignment/{id}', [assignmentController::class, 'getSubmittedAssignments'])->name('submitted-assignment');

    Route::post('/mark-assignment/', [marksController::class, 'markAssignment'])->name('mark-assignment');

    Route::get('/get-relevent-batches/{id}', [teacherController::class, 'getReleventBatches'])->name('get-relevent-batches');

    Route::post('/get-relevent-info-batches/{id}', [teacherController::class, 'getInfoForBatches'])->name('getInfoForBatches');

    Route::post('/get-relevent-students-info/{id}', [teacherController::class, 'getReleventStudents'])->name('getReleventStudents');

    Route::post('/submit-attendance/{id}', [attendanceController::class, 'submitAttendance'])->name('getReleventStudents');


    Route::get('/show-students/{id}', [attendanceController::class, 'getStudents'])->name('show-students');

    Route::get('/check-attendance-marked/{id}', [attendanceController::class, 'checkAttendanceMarked'])->name('checkAttendanceMarked');

    Route::get('get-total-classes/{id}', [attendanceController::class, 'totalClasses'])->name('totalClasses');

    Route::get('/student/attendance/{id}', [AttendanceController::class, 'showAttendance'])->name('attendance.show');

    Route::post('/student/attendance/update/{id}', [AttendanceController::class, 'updateStatus']);
});




Route::prefix("/dashboard/staff/")->middleware(['auth', 'staff'])->group(function () {
    Route::view('/home/{id}', 'staff.pages.dashboard')->name('staff-dashboard');

    Route::view('/courses/view-courses/{id}', 'staff.pages.view-courses')->name('staff-view-courses');

    Route::view('/courses/add-courses/{id}', 'staff.pages.add-courses')->name('staff-add-courses');

    Route::view('/teachers/add-teachers/{id}', 'staff.pages.add-teachers')->name('staff-add-teachers');

    Route::view('/teachers/view-teachers/{id}', 'staff.pages.view-teachers')->name('staff-view-teachers');

    Route::view('/batches/add-batches', 'staff.pages.add-batches')->name('staff-add-batches');

    Route::view('/batches/view-batches/', 'staff.pages.view-batches')->name('staff-view-batches');

    Route::view('/batches/assign-batches/{id}', 'staff.pages.assign-batches')->name('staff-assign-batches');

    Route::view('/student/add-student', 'staff.pages.add-students')->name('staff-add-students');

    Route::view('/get-students', 'staff.pages.view-students')->name('staff-view-students');






    Route::post('/add-course-data', [courseController::class, 'addCourse'])->name('add-courses-data');

    Route::get('/get-course-data', [courseController::class, 'getCourses'])->name('get-courses-data');

    Route::post('/add-instructor', [staffController::class, 'addInstructor'])->name('staff.addInstructor');

    Route::get('/get-courses', [batchController::class, 'getCourses']);

    Route::post('/get-teachers', [batchController::class, 'getTeachers']);

    Route::post('/add-batch', [batchController::class, 'addBatch']);


    Route::get('/batches/view-batches', [batchController::class, 'getBatches'])->name('staff-view-batches');

    Route::get('/get-students', [staffController::class, 'getStudents'])->name('staff-view-students');


    Route::post('/update-batch/{id}', [BatchController::class, 'updateBatch'])->name('batches.update');

    Route::delete('/delete-batch/{id}', [BatchController::class, 'deleteBatch'])->name('batches.delete');

    Route::get('/batches/{id}/edit', [BatchController::class, 'editBatch']);

    Route::post('/get-teachers-and-duration', [BatchController::class, 'getTeachersAndDuration']);

    Route::post('/add-student', [StaffController::class, 'storeStudent'])->name('students.store');

    Route::post('/get-batches', [StaffController::class, 'getBatchesStudent'])->name('staff.getBatches');



    // Route to update a student

    // Route to get a single student's details (for editing)
    Route::get('/edit-student/{id}', [staffController::class, 'editStudent'])->name('students.edit');


    // Route to update user
    Route::post('/update-student/{id}', [StaffController::class, 'updateUser'])->name('staff.updateUser');

    // Route to delete user
    Route::delete('/delete-student/{id}', [StaffController::class, 'deleteUser'])->name('staff.deleteUser');

    // Route to fetch a user's details for editing
    Route::get('/get-student/{id}', [StaffController::class, 'editUser'])->name('staff.editUser');

    Route::get('/get-batches/{courseId}', [batchController::class, 'getBatches']);
})->middleware(['auth', 'staff']);



// user routes

Route::post('/sign-in', [UserController::class, 'signIn'])->name('sign-in');
Route::post('/sign-out', [UserController::class, 'signOut'])->name('sign-out');
