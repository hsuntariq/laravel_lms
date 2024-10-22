<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editStudentForm" class="student-edit-form">
                    @csrf
                    <input type="hidden" id="edit_student_id" name="student_id" />

                    <!-- Name -->
                    <div class="form-group">
                        <label for="edit_student_name">Name</label>
                        <input type="text" name="student_name" class="form-control" id="edit_student_name"
                            required />
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="edit_student_email">Email</label>
                        <input type="email" name="student_email" class="form-control" id="edit_student_email"
                            required />
                    </div>

                    <!-- WhatsApp -->
                    <div class="form-group">
                        <label for="edit_student_whatsapp">WhatsApp</label>
                        <input type="text" name="student_whatsapp" class="form-control" id="edit_student_whatsapp"
                            required />
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label for="edit_student_gender">Gender</label>
                        <select name="gender" class="form-control" id="edit_student_gender" required>
                            <option value="" disabled selected>Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <!-- Courses -->
                    <div class="form-group">
                        <label for="edit_course">Course</label>
                        <select name="course" class="form-control" id="edit_course" required>
                            <option value="" disabled selected>Select Course</option>
                            <!-- Courses will be populated dynamically -->
                        </select>
                    </div>

                    <!-- Batches -->
                    <div class="form-group">
                        <label for="edit_batch">Batch</label>
                        <select name="batch" class="form-control" id="edit_batch" required>
                            <option value="" disabled selected>Select Batch</option>
                            <!-- Batches will be populated dynamically -->
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary update-student-btn" disabled>Update Student</button>
                </form>
            </div>
        </div>
    </div>
</div>
