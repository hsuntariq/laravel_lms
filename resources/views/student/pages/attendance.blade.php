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
                <div class="table-responsive  my-4">
                    <table class="table text-center attendance-table text-capitalize ">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td>Date</td>
                                <td>Day</td>
                                <td>Class Start time</td>
                                <td>Class End time</td>
                                <td>status</td>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="p-4">{{ $i + 1 }}</td>
                                    <td class="p-4">{{ now()->format('d-m-y') }}</td>
                                    <td class="p-4">Tuesday</td>
                                    <td class="p-4">12.45am</td>
                                    <td class="p-4">2.45am</td>
                                    <td class="p-4">present</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                </div>
                </div>
    </main>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
</x-layout>
