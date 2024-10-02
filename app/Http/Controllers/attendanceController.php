<?php

namespace App\Http\Controllers;

use App\Charts\attendanceChart;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
    public function makeCharts()
    {
        $pieChart = new attendanceChart;
        $doughnetChart = new attendanceChart;
        $radarChart = new attendanceChart;
        $pieChart->labels(['presents', 'absents']);
        $pieChart->dataset('presents', 'pie', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['presents', 'absents']);
        $doughnetChart->dataset('presents', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);

        return view('student.pages.attendance', compact('pieChart', 'doughnetChart', 'radarChart'));
    }
    public function makeCharts2()
    {
        $pieChart = new attendanceChart;
        $doughnetChart = new attendanceChart;
        $radarChart = new attendanceChart;
        $pieChart->labels(['presents', 'absents']);
        $pieChart->dataset('presents', 'pie', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);
        $doughnetChart->labels(['presents', 'absents']);
        $doughnetChart->dataset('presents', 'doughnut', [40, 10])->options([
            'backgroundColor' => ['green', 'red']
        ]);

        return view('teacher.pages.view-attendance', compact('pieChart', 'doughnetChart', 'radarChart'));
    }
}