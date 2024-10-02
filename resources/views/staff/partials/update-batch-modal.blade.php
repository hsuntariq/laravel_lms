<div id="updateBatchModal" class="modal fade" tabindex="-1" aria-labelledby="updateBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateBatchModalLabel">Update Batch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateBatchForm">
                    @csrf
                    <div class="mb-3">
                        <label for="batchNo" class="form-label">Batch Number</label>
                        <input type="text" class="form-control" id="batchNo" name="batch_no" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseAssigned" class="form-label">Course</label>
                        <select class="form-select" id="courseAssigned" name="course_id" required>
                            <!-- Course options will be dynamically populated here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="teacherAssigned" class="form-label">Teacher</label>
                        <select class="form-select" id="teacherAssigned" name="teacher" required>
                            <!-- Teachers will be dynamically loaded here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="duration" class="form-label">Duration (in months)</label>
                        <input type="text" class="form-control" id="duration" name="duration" required readonly>
                    </div>
                    <input type="hidden" id="batchId" name="batch_id"> <!-- Hidden input for batch ID -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-purple btn-sm" id="saveBatchBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>
