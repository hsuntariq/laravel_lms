<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;

class studentController extends Controller
{
    public function countData()
    {
        $student_batch = auth()->user()->batch_assigned;
        $assignments = Assignment::where('batch_no', $student_batch)->where('type', 'assignment')->count();
        $tests = Assignment::where('batch_no', $student_batch)->where('type', 'test')->count();

        return response()->json([
            'assignments' => $assignments,
            'tests' => $tests,
        ]);
    }
}
