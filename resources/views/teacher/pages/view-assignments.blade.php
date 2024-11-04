<x-teacher-dashboard-layout>
    <section style="height:89vh;overflow-y:scroll;">
        @include('teacher.partials.header')
        <section class="filter justify-content-between my-2 d-flex align-items-center">
            <form action="" method="POST"
                class="form-control form-control-sm p-0 px-3 d-flex align-items-center rounded-pill gap-2 w-25">
                <i class="bi bi-search" style="color:#8338EB"></i>
                <input type="text" class="border-0 w-100 input-search" style="outline-width:0"
                    placeholder="Seach by student...">
            </form>

            <ul class="d-flex m-0 align-items-center list-unstyled text-purple fw-semibold text-sm gap-2">
                <li><button class="filter-button btn text-sm fw-semibold" data-filter="all">All</button></li>
                <li><button class="filter-button btn text-sm fw-semibold" data-filter="marked">Marked</button></li>
                <li><button class="filter-button btn text-sm fw-semibold" data-filter="unmarked">Unmarked</button></li>
            </ul>



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
            <table class="table text-capitalize table-sm table-bordered table-striped text-sm btn-sm input-group-sm">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>day</td>
                        <td>student</td>
                        <td>topic</td>
                        <td>batch</td>
                        <td>submit time</td>
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</x-teacher-dashboard-layout>
