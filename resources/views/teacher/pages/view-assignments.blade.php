<x-teacher-dashboard-layout>
    <section style="height:89vh;overflow-y:scroll;">
        @include('teacher.partials.header')
        <section class="filter my-2">
            <form action="" method="POST" class="form-control d-flex align-items-center rounded-pill gap-2 w-25">
                <div class="bi bi-search" style="color:#8338EB"></div>
                <input type="text" class="border-0 w-100 input-search" style="outline-width:0"
                    placeholder="Seach by date or student...">
            </form>
        </section>
        <section class="row justify-content-between">
            <div class="col-sm-4" style="height:200px">

                {!! $pieChart->container() !!}
            </div>
            <div class="col-sm-4" style="height:200px">

                {!! $doughnetChart->container() !!}
            </div>
            <div class="col-sm-4" style="height:200px">

                {!! $lineChart->container() !!}
            </div>

        </section>
        <div class="table-responsive">
            @include('student.partials.table-loader')
            <table class="table text-capitalize">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>day</td>
                        <td>student</td>
                        <td> time</td>
                        <td>File</td>
                        <td>Max Marks</td>
                        <td>Obt marks</td>
                        <td>Mark</td>

                    </tr>
                </thead>
                <tbody class='submitted-assignments'>

                </tbody>
            </table>
        </div>
    </section>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
    {!! $lineChart->script() !!}
</x-teacher-dashboard-layout>
