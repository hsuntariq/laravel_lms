<?php

namespace App\Http\Controllers;

use App\Charts\teacherDashboardChart;
use Illuminate\Http\Request;

class teacherDashboardController extends Controller
{
    public function makeCharts()
    {
        $doughnetChart = new teacherDashboardChart;
        $doughnetChart->dataset('presents', 'doughnut', [70, 15, 5, 10])->options([
            'backgroundColor' => ['green', 'blue', 'yellow', 'red']
        ]);

        return view('teacher.pages.dashboard', compact('doughnetChart'));
    }
}
