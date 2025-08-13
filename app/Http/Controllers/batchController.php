<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class batchController extends Controller
{
    public function getCourses()
    {
        $courses = Course::all();
        return response()->json($courses);
    }

    public function getTeachers(Request $request)
    {
        $course_id = $request->course_id;
        $teachers = User::where('role', 'teacher')->where('course_assigned', $course_id)->get();
        return response()->json($teachers);
    }



    public function addBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_name_batch' => 'required|exists:courses,id',
            'batch_number' => 'required|integer',
            'teacher_assigned' => 'required|exists:users,id',
            'branch' => 'required|string',
            'days' => 'required|array|min:1',
            'class_links' => 'required|array|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $existingBatch = Batch::where('batch_no', $request->input('batch_number'))
            ->where('course_id', $request->input('course_name_batch'))
            ->first();

        if ($existingBatch) {
            return response()->json(['status' => 'error', 'errors' => ['batch_number' => 'Batch number already exists for this course.']], 422);
        }

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // Convert 12-hour format to 24-hour format
        $startTime = Carbon::createFromFormat('g:i A', $request->input('start_time'))->format('H:i:s');
        $endTime = Carbon::createFromFormat('g:i A', $request->input('end_time'))->format('H:i:s');

        $batch = new Batch();
        $batch->course_id = $request->input('course_name_batch');
        $batch->batch_no = $request->input('batch_number');
        $batch->teacher = $request->input('teacher_assigned');
        $batch->branch = $request->input('branch');
        $batch->days = json_encode($request->input('days'));
        $batch->class_links = json_encode($request->input('class_links'));
        $batch->start_date = $request->input('start_date');
        $batch->end_date = $request->input('end_date');
        $batch->start_time = $startTime;
        $batch->end_time = $endTime;
        $batch->save();

        return response()->json(['status' => 'success', 'message' => 'Batch added successfully!', 'batch' => $batch]);
    }




    public function getBatches(Request $request)
    {
        if ($request->has('course_id') && $request->course_id != '') {
            $batches = Batch::where('course_id', $request->course_id)
                ->paginate(3);
        } else {
            $batches = Batch::paginate(3);
        }

        // Always return JSON for AJAX
        return response()->json([
            'batches' => $batches
        ]);
    }




    public function updateBatch(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'batch_no' => 'required|integer',
            'teacher' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'branch' => 'required|string',
            'days' => 'required|array|min:1',
            'class_links' => 'required|array|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $validatedData = $validator->validate();

        $existingBatch = Batch::where('batch_no', $validatedData['batch_no'])
            ->where('course_id', $validatedData['course_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingBatch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Batch number already exists for this course.'
            ], 422);
        }

        $batch->update([
            'batch_no' => $validatedData['batch_no'],
            'teacher' => $validatedData['teacher'],
            'course_id' => $validatedData['course_id'],
            'branch' => $validatedData['branch'],
            'days' => json_encode($validatedData['days']),
            'class_links' => json_encode($validatedData['class_links']),
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch updated successfully!',
            'batch' => $batch
        ]);
    }



    public function deleteBatch($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Batch deleted successfully',
        ]);
    }




    public function editBatch($batchId)
    {
        $batch = Batch::find($batchId);

        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found']);
        }

        $courses = Course::all();
        $teachers = User::where('role', 'teacher')
            ->where('course_assigned', $batch->course_id)
            ->get();

        return response()->json([
            'batch' => $batch,
            'courses' => $courses,
            'teachers' => $teachers,
        ]);
    }





    public function getTeachersAndDuration(Request $request)
    {
        $courseId = $request->course_id;

        // Fetch teachers who are assigned to the selected course
        $teachers = User::where('role', 'teacher')
            ->where('course_assigned', $courseId)
            ->get();

        // Fetch the course duration
        $course = Course::find($courseId);

        return response()->json([
            'teachers' => $teachers,
            'course_duration' => $course->course_duration,
        ]);
    }
}
