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
            "title" => 'required|string|unique:courses,title',
            "category" => 'required|string',
            "level" => 'required|string',
            "language" => 'required|string',
            "visibility" => 'required|string',
            "short_description" => 'nullable|string',
            "price" => 'required|numeric',
            "thumbnail" => 'nullable|image',
            "description" => 'nullable|string',
            "featured" => 'nullable|boolean',
            "learning" => 'nullable|array',
            "requirements" => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('_token');
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['learning'] = json_encode($request->input('learning', []));
        $data['requirements'] = json_encode($request->input('requirements', []));

        $course = Course::create($data);

        return response()->json(['status' => 'success', 'course' => $course]);
    }



    public function getCourses(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $courses = Course::paginate($perPage);
        return response()->json($courses);
    }
    public function getStudentsCourse()
    {
        $courses = User::where('id', auth()->user()->id)->with('courses')->get();
        return response()->json($courses);
    }
    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(['status' => 'success']);
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "title" => 'required|string|unique:courses,title,' . $id,
            "category" => 'required|string',
            "level" => 'required|string',
            "language" => 'required|string',
            "visibility" => 'required|string',
            "short_description" => 'nullable|string',
            "price" => 'required|numeric',
            "thumbnail" => 'nullable|image',
            "description" => 'nullable|string',
            "featured" => 'nullable|boolean',
            "learning" => 'nullable|array',
            "requirements" => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('_token');
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $data['featured'] = $request->has('featured') ? 1 : 0;
        $data['learning'] = json_encode($request->input('learning', []));
        $data['requirements'] = json_encode($request->input('requirements', []));

        $course->update($data);

        return response()->json(['status' => 'success', 'course' => $course]);
    }
}
