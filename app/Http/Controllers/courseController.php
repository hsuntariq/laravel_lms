<?php

namespace App\Http\Controllers;

use App\Charts\courseChart;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class courseController extends Controller
{
    public function makeCharts()
    {
        $pieChart = new courseChart;
        $doughnetChart = new courseChart;
        $radarChart = new courseChart;
        $pieChart->labels(['Current Classes', 'Remaining']);
        $pieChart->dataset('presents', 'pie', [72, 30])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['Current Classes', 'Remaining']);
        $doughnetChart->dataset('presents', 'doughnut', [72, 30])->options([
            'backgroundColor' => ['green', 'red']
        ]);

        return view('student.pages.attendance', compact('pieChart', 'doughnetChart', 'radarChart'));
    }


    public function addCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "course_name" => 'required',
            "course_duration" => 'required',
            "course_fee" => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        };

        // check existing course
        $checkExisting = Course::where('course_name', $request->input('course_name'))->first();

        if ($checkExisting) {
            return response()->json([
                'status' => 'already present',
                'message' => 'Course already present'
            ], 400);
        } else {

            $formFields = $request->except('_token');

            $course = Course::create($formFields);
            return response()->json($course);
        }
    }



    public function getCourses()
    {
        $courses = Course::all();
        return response()->json($courses);
    }
    public function getStudentsCourse()
    {
        $courses = User::where('id', auth()->user()->id)->with('courses')->get();
        return response()->json($courses);
    }
}
