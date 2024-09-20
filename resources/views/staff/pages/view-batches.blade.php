<x-staff-dashboard-layout>
    <div class="max-width">
        <h2 class="mb-4 display-5">Current Batches</h2>
        <div class="underline"></div>
    </div>
    <div class="table-responsive my-2">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Batch No</th>
                    <th>Teacher Assigned</th>
                    <th>Course</th>
                    <th>Duration</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody class="batches-view">
                @foreach ($batches as $batch)
                <tr>
                    <td>{{ $batch->id }}</td>
                    <td>{{ $batch->batch_no }}</td>
                    <td>{{ optional($batch->teachers)->name ?? 'N/A' }}</td> <!-- Use optional() to prevent errors -->
                    <td>{{ optional($batch->course)->course_name ?? 'N/A' }}</td> <!-- Use optional() to prevent errors -->
                    <td>{{ $batch->course->course_duration }} months</td> <!-- Adjust according to your actual data -->
                    <td>
                        <button class="btn btn-purple btn-sm">
                            Update
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div class="d-flex justify-content-between">
            {{ $batches->links("pagination::bootstrap-4") }}
        </div>
        </table>

    </div>

</x-staff-dashboard-layout>