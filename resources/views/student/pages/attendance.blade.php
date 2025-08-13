<x-student-dashboard-layout>


    <section class="col-xl-10 col-lg-9 p-5" style='height:90vh;overflow-y:scroll'>
        <div class="max-width">
            <h1>Attendance</h1>
            <div class="underline"></div>
        </div>
        <div class="d-flex justify-content-center gap-3 align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <div class='attendance-box bg-secondary'></div>
                <p class="text-purple m-0 text-sm fw-medium total-classes-student"></p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class='attendance-box bg-success'></div>
                <p class="text-purple m-0 text-sm fw-medium total-present-student"></p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <div class='attendance-box bg-danger'></div>
                <p class="text-purple m-0 text-sm fw-medium total-absent-student"></p>
            </div>
        </div>
        {{-- here goes the chart --}}
        <section class="row justify-content-between">
            <div class="col-sm-6" style="height:200px">

                {!! $pieChart->container() !!}
            </div>
            <div class="col-sm-6" style="height:200px">

                {!! $doughnetChart->container() !!}
            </div>

        </section>
        <section class="table-responsive">
            <div class="table-responsive text-sm    my-4">
                @include('student.partials.table-loader')
                <table class="table text-center table-bordered table-striped attendance-table text-capitalize ">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Date</td>
                            <td>Day</td>
                            <td>Topic</td>
                            <td>Remarks</td>
                            <td>status</td>
                        </tr>
                    </thead>
                    <tbody class='student-attendance-table'>

                    </tbody>

                </table>
            </div>
        </section>
        {!! $pieChart->script() !!}
        {!! $doughnetChart->script() !!}

</x-student-dashboard-layout>
