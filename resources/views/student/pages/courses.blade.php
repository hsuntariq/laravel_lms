<x-layout>
    <x-header />
    <hr class="my-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
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
    </main>
</x-layout>