<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        ]);

        // Check if batch number is unique for the selected course
        $existingBatch = Batch::where('batch_no', $request->input('batch_number'))
            ->where('course_id', $request->input('course_name_batch'))
            ->first();

        if ($existingBatch) {
            return response()->json(['errors' => ['batch_number' => 'Batch number already exists for this course.']], 422);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $batch = new Batch();
        $batch->course_id = $request->input('course_name_batch');
        $batch->batch_no = $request->input('batch_number');
        $batch->teacher = $request->input('teacher_assigned');
        $batch->save();

        return response()->json(['status' => 'success', 'message' => 'Batch added successfully!']);
    }




    public function getBatches(Request $request)
    {
        $batches = Batch::with(['teachers', 'course'])
            ->paginate(10);


        if ($request->ajax()) {
            $batchesHtml = view('staff.partials.batches', compact('batches'))->render();
            $paginationHtml = view('staff.partials.pagination', compact('batches'))->render();

            return response()->json([
                'batchesHtml' => $batchesHtml,
                'paginationHtml' => $paginationHtml,
            ]);
        }



        return view('staff.pages.view-batches', compact('batches'));
    }



    public function updateBatch(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $validatedData = $request->validate([
            'batch_no' => 'required|integer',
            'teacher' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        // Check for uniqueness of batch number within the course context (excluding the current batch being updated)
        $existingBatch = Batch::where('batch_no', $validatedData['batch_no'])
            ->where('course_id', $validatedData['course_id'])
            ->where('id', '!=', $id) // Exclude the current batch
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
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Batch updated successfully!',
            'batchesHtml' => view('staff.partials.batches', compact('batches'))->render(),
            'paginationHtml' => view('staff.partials.pagination', compact('batches'))->render(),
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
        $batch = Batch::with('course', 'teachers')->find($batchId);

        if (!$batch) {
            return response()->json(['status' => 'error', 'message' => 'Batch not found']);
        }

        $courses = Course::all();
        $courseOptions = '<option disabled selected>Select Course</option>';
        foreach ($courses as $course) {
            $selected = $batch->course_id == $course->id ? 'selected' : '';
            $courseOptions .= "<option value='{$course->id}' $selected>{$course->course_name}</option>";
        }

        $teachers = User::where('role', 'teacher')
            ->where('course_assigned', $batch->course_id)
            ->get();
        $teacherOptions = '<option disabled selected>Select Teacher</option>';
        foreach ($teachers as $teacher) {
            $selected = $batch->teacher == $teacher->id ? 'selected' : '';
            $teacherOptions .= "<option value='{$teacher->id}' $selected>{$teacher->name}</option>";
        }

        return response()->json([
            'batch_no' => $batch->batch_no,
            'course_id' => $batch->course_id,
            'teacher' => $batch->teacher,
            'duration' => $batch->course->course_duration,
            'courseOptions' => $courseOptions,
            'teacherOptions' => $teacherOptions,
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
