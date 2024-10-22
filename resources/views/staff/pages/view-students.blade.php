<x-staff-dashboard-layout>
    <div class="">
        <div class="d-flex align-items-center justify-content-between">
            <h2 class="mb-2 display-5">View Students</h2>
            <div class="filter d-flex w-50 align-items-centers gap-3">
                {{-- <i class="bi bi-filter fs-3"></i> --}}
                <select name="" class="form-control courses-select" id="">
                    <option disabled selected>Select Course</option>
                    {{-- populated by js --}}
                </select>
                <select name="" class="form-control batch-select" id="">
                    {{-- populated by js according to the course selected --}}
                    <option disabled selected>Select Batch</option>
                </select>
            </div>
        </div>
        <div class="underline w-25"></div>
        <h6 class="text-purple my-3 total-students">

        </h6>
    </div>
    <!-- Table Loader -->
    @include('staff.partials.table-loader')

    <!-- Student Table -->
    <div class="table-responsive student-table" style="height:85vh; overflow-y:scroll">
        <table class="table text-capitalize">
            <thead>
                <tr>
                    <td>ID</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Batch</td>
                    <td>Course</td>
                    <td>Delete</td>
                    <td>Update</td>
                </tr>
            </thead>
            <tbody class="students">
                <!-- Student rows will be populated by JS -->
            </tbody>
        </table>
        <div class="d-flex justify-content-between student-pagination">
            {{ $students->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @include('staff.partials.edit-student')

</x-staff-dashboard-layout>