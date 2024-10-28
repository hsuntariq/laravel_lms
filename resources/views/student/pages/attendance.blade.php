<x-layout>
    <x-header />
    <hr class="my-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-10 col-lg-9" style='height:90vh;overflow-y:scroll'>
            <h2>Attendance</h2>
            <div class="d-flex justify-content-center gap-3 align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <div class='attendance-box bg-secondary'></div>
                    <p class="text-purple m-0 text-sm fw-medium">Total(10)</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class='attendance-box bg-success'></div>
                    <p class="text-purple m-0 text-sm fw-medium ">Present(9)</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <div class='attendance-box bg-danger'></div>
                    <p class="text-purple m-0 text-sm fw-medium ">Absents(1)</p>
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
                </div>
                </div>
    </main>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
</x-layout>
