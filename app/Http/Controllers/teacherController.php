<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\User;
use Illuminate\Http\Request;

class teacherController extends Controller
{
    public function getReleventBatches()
    {
        $teacher_id = auth()->user()->id;
        $batches = Batch::where('teacher', $teacher_id)->get();
        return response()->json($batches);
    }


    public function getReleventStuents($batch_no)
    {
        $students = User::where('role', 'student')->where('batch_assigned', $batch_no)->count();
        return response()->json([
            "students" => $students,
        ]);
    }
}
