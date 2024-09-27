<x-staff-dashboard-layout>
    <!-- Include partial for table loader -->
    @include('student.partials.table-loader')

    <!-- Student Table -->
    <div class="table-responsive student-table" style="height:85vh;overflow-y:scroll">
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
    </div>

    <!-- Edit Student Modal (Hidden by default) -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="student-edit-form">
                        @csrf
                        <input type="hidden" name="student_id" />
                        <div class="form-group">
                            <label for="student_name">Name</label>
                            <input type="text" name="student_name" class="form-control" id="student_name" />
                        </div>
                        <div class="form-group">
                            <label for="student_email">Email</label>
                            <input type="email" name="student_email" class="form-control" id="student_email" />
                        </div>
                        <div class="form-group">
                            <label for="batch">Batch</label>
                            <select name="batch" class="form-control" id="batch">
                                <!-- Populate with batches via JS -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary update-student-btn">Update Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-staff-dashboard-layout>