<x-layout>
    <x-header />
    <x-error />
    {{-- <x-flash /> --}}
    <x-toast />
    <hr class="m-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-10 col-lg-9 p-3">
            <section class="d-flex align-items-center px-3 justify-content-between">
                <div class="d-flex">
                    <div class="max-width">
                        <h1>Assignments</h1>
                        <div class="underline"></div>
                    </div>
                </div>
                <ul class="d-flex m-0 align-items-center list-unstyled text-purple fw-semibold text-sm gap-2">
                    <li class="filter-button-student btn text-sm btn-sm btn-purple" data-status="all">All</li>
                    <li class="filter-button-student btn text-sm btn-sm" data-status="submitted">Submitted</li>
                    <li class="filter-button-student btn text-sm btn-sm" data-status="unsubmitted">Unsubmitted</li>
                </ul>


            </section>
            <div class="table-responsive assignment-table text-sm my-4" style='height:75vh;overflow-y:scroll'>
                @include('student.partials.table-loader')
                <table class="table text-center table-sm table-striped table-bordered">
                    <thead>
                        <tr>
                            <td class='text-sm'>#</td>
                            <td class='text-sm'>Topic</td>
                            <td class='text-sm'>Marks</td>
                            <td class='text-sm'>End Time</td>
                            <td class='text-sm'>Upload Time</td>
                            <td class='text-sm'>File</td>
                            <td class='text-sm'>Status</td>
                            <td class='text-sm' style="width:20%">Upload</td>
                            <td class='text-sm'>Action</td>


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