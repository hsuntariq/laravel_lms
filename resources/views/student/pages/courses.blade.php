<x-student-dashboard-layout>



    <div class="col-xl-10 col-lg-9 p-5">
        <div class="max-width">
            <h1>Registered Courses</h1>
            <div class="underline"></div>
        </div>
        @include('student.partials.table-loader')
        <div class="table-responsive my-3 ">

            <table class="table table-striped table-bordered table-sm  text-capitalize">
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Course name</td>
                        <td>Batch </td>
                        <td>Course duration</td>
                    </tr>
                </thead>
                <tbody class="student-courses">

                </tbody>
            </table>
        </div>
    </div>
</x-student-dashboard-layout>
