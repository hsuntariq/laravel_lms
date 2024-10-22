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
            <div class="col-sm-6" style="height:200px">

                {!! $pieChart->container() !!}
            </div>
            <div class="col-sm-6" style="height:200px">

                {!! $doughnetChart->container() !!}
            </div>

        </section>
        <div class="table-responsive" ">
        <table class="table text-capitalize">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>day</th>
                    <th>student</th>
                    <th>status</th>
                    <th>update</th>
                </tr>
            </thead>
            <tbody>
                          @for ($i=0; $i < 10; $i++)
            <tr>
                <td>21-01-21</td>
                <td>Tuesday</td>
                <td>Sara</td>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dot bg-success rounded-circle"></div>
                        present
                    </div>
                </td>
                <td>
                    <button class="btn text-white btn-purple">
                        Update
                    </button>
                </td>
            </tr>
            @endfor
            </tbody>
            </table>
        </div>
    </section>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
</x-teacher-dashboard-layout>
