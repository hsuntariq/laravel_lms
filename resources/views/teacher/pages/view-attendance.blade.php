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
        <div class="table-responsive">
            @include('teacher.partials.attendance-modal')

            @include('teacher.partials.table-loader')
            <table class="table text-capitalize table-sm table-striped table-bordered text-sm">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>name</th>
                        <th>percentage</th>
                        <th>view</th>
                    </tr>
                </thead>
                <tbody class='student-attendance-teacher'>

                </tbody>
            </table>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}






</x-teacher-dashboard-layout>
