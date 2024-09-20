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
            'course_name_batch' => 'required',
            'batch_number' => 'required|integer',
            'teacher_assigned' => 'required',
        ]);

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
            ->paginate(3);


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
}
