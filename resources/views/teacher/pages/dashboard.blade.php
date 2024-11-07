<x-teacher-dashboard-layout>
    @include('teacher.partials.header')
    <div class="row" style="height: 80vh;overflow-y:scroll">
        <div class="col-lg-6">
            @include('teacher.partials.statistics')
            @include('teacher.partials.student-details')
        </div>

        <div class="col-lg-6">
            @include('teacher.partials.performance')
            @include('teacher.partials.top-students')



        </div>
    </div>


</x-teacher-dashboard-layout>