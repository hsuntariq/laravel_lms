<x-layout>
    <x-header />
    <x-flash />
    <hr class="m-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-10 col-lg-9 p-3">
            <section class="d-flex align-items-center px-3 justify-content-between">
                <div class="d-flex">
                    <h2>Assignments</h2>
                </div>
                <ul class="list-unstyled text-capitalize d-flex gap-3 fw-medium">
                    <li style="color: #8338EB">All</li>

                </ul>
            </section>
            <div class="table-responsive assignment-table my-4" style='height:75vh;overflow-y:scroll'>
                @include('student.partials.table-loader')
                <table class="table text-center text-sm">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Topic</td>
                            <td>Marks</td>
                            <td>End Time</td>
                            <td>Upload Time</td>
                            <td>Status</td>
                            <td style="width:20%">Upload</td>
                            <td>Action</td>


                        </tr>
                    </thead>
                    <tbody id="assignmentsTableBody">
                        <!-- Assignments will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</x-layout>
