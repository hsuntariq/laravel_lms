<x-layout>
    <x-header />
    <hr class="my-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-10 col-lg-9 p-3">
            <div class="max-width">
                <h1>Assignments</h1>
                <div class="underline"></div>
            </div>
            @include('student.partials.table-loader')
            <div class="table-responsive hide-table">
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

                        </tr>
                    </thead>
                    <tbody class='marks-table'>

                    </tbody>
                </table>
            </div>
            <div class="max-width">
                <h1>Tests</h1>
                <div class="underline"></div>
            </div>
            @include('student.partials.table-loader')

            <div class="table-responsive hide-table">
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

                        </tr>
                    </thead>
                    <tbody class='marks-test-table'>

                    </tbody>
                </table>
            </div>
        </section>
    </main>
</x-layout>
