<x-teacher-dashboard-layout>
    @include('teacher.partials.header')
    @include('teacher.partials.table-loader')

    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>
    <!-- Header with Search -->
    <div class="container-fluid p-4">
        <div class="row mb-4 align-items-center">
            <!-- <div class="col-md-6">
                <h3 class="text-purple d-flex align-items-center gap-3 fw-bold mb-0 text-capitalize header-title">
                    <img width="40px" src="https://cdn-icons-png.freepik.com/512/9052/9052143.png" alt=""> All
                    Assignments
                </h3>
            </div> -->
            <div class="col-md-6">
                <div class="search-container">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent border-purple search-icon"><i
                                class="bi bi-search text-purple"></i></span>
                        <input type="text" class="form-control border-purple search-input"
                            placeholder="Search by topic, description, or batch..." style="outline-width:0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-border-secondary text-sm modern-table"
                    id="assignments-table2">
                    <thead class="table-header">
                        <tr>
                            <th>ID</th>
                            <th>Topic</th>
                            <th>Description</th>
                            <th>Max Marks</th>
                            <th>Batch No</th>
                            <th>Deadline</th>
                            <th>Type</th>
                            <th>File</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Assignment Modal -->
    <div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modern-modal">
                <div class="modal-header bg-purple text-white modal-header-gradient">
                    <h5 class="modal-title" id="editAssignmentModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Assignment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-enhanced">
                    <input type="hidden" id="edit-assignment-id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-topic" class="form-label text-purple fw-semibold">Topic</label>
                                <input type="text" class="form-control form-control-enhanced" id="edit-topic"
                                    placeholder="Enter topic">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-description"
                                    class="form-label text-purple fw-semibold">Description</label>
                                <textarea class="form-control form-control-enhanced" id="edit-description"
                                    placeholder="Enter description" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-max-marks" class="form-label text-purple fw-semibold">Max Marks</label>
                                <input type="number" class="form-control form-control-enhanced" id="edit-max-marks"
                                    min="0" placeholder="Enter max marks">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-batch-no" class="form-label text-purple fw-semibold">Batch No</label>
                                <select name="batch_no" id="edit-batch-no"
                                    class="form-select form-select-sm border-purple text-purple fw-medium bg-transparent form-select-enhanced">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-deadline" class="form-label text-purple fw-semibold">Deadline</label>
                                <input type="datetime-local" class="form-control form-control-enhanced"
                                    id="edit-deadline">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group-enhanced">
                                <label for="edit-type" class="form-label text-purple fw-semibold">Type</label>
                                <input type="text" class="form-control form-control-enhanced" id="edit-type"
                                    placeholder="Enter type">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 form-group-enhanced">
                        <label for="edit-file" class="form-label text-purple fw-semibold">File</label>
                        <input type="hidden" id="edit-file-current" value="">
                        <div class="mb-2" id="edit-file-display"></div>
                        <input type="file" class="form-control form-control-enhanced" id="edit-file" name="edit-file">
                    </div>
                </div>
                <div class="modal-footer bg-light modal-footer-enhanced">
                    <button type="button" class="btn btn-secondary btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveAssignmentBtn" class="btn btn-purple btn-save-enhanced">
                        <span class="btn-text">Save</span>
                        <span class="btn-spinner d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <x-jquery />

    <script>
    $(document).ready(function() {
                let user_id = window.location.pathname.split('/').pop();

                // Initial fetch with default batch_no (if any)
                fetchAssignments();

                // Fetch assignments when batch_no changes
                $('select[name="batch_no"]').on('change', function() {
                    fetchAssignments();
                });

                // Search functionality with keyword highlighting
                $('.search-input').on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $('#assignments-table2 tbody tr').each(function() {
                        const $row = $(this);
                        const text = $row.text().toLowerCase();
                        $row.find('td:not(:last-child)').each(function() {
                            const $cell = $(this);
                            let cellText = $cell.text();
                            $cell.html(cellText); // Reset to original text
                            if (searchTerm && text.includes(searchTerm)) {
                                const regex = new RegExp(`(${searchTerm})`, 'gi');
                                $cell.html(cellText.replace(regex,
                                    '<span style="background-color: yellow;">$1</span>'
                                ));
                            }
                        });
                        $row.toggle(text.includes(searchTerm));
                    });
                });

                function displayFile(file) {
                    const ext = file?.split(".").pop();
                    const fileUrl = `/laravel/public/external_uploads/${file}`;
                    const fileIcons = {
                        html: "/assets/file_icons/html.png",
                        png: "/assets/file_icons/png.webp",
                        jpeg: "/assets/file_icons/jpeg.png",
                        docx: "/assets/file_icons/docx.webp",
                        jpg: "/assets/file_icons/jpg.png",
                        default: "/assets/file_icons/default.png",
                    };

                    return file ?
                        `<a href='${fileUrl}' download class='text-decoration-none'><img width='30px' height='30px' src='${fileIcons[ext] || fileIcons.default}' alt='file icon'></a>` :
                        'No file';
                }

                function fetchAssignments() {
                    $('.loader-table-teacher').show();
                    let batch_no = $('select[name="batch_no"]').val();

                    $.ajax({
                        url: `/dashboard/teacher/get-all-assignments/${user_id}`,
                        type: 'GET',
                        data: {
                            batch_no
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#assignments-table2 tbody').empty();
                            $('.loader-table-teacher').hide();
                            if (response.length > 0) {
                                response.forEach(assignment => {
                                    const deadline = assignment.deadline ? new Date(assignment
                                        .deadline).toLocaleString('en-US', {
                                        timeZone: "Asia/Karachi",
                                        month: "2-digit",
                                        day: "2-digit",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        hour12: true
                                    }) : 'N/A';
                                    const createdAt = assignment.created_at ? new Date(assignment
                                        .created_at).toLocaleString('en-US', {
                                        timeZone: "Asia/Karachi",
                                        month: "2-digit",
                                        day: "2-digit",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        hour12: true
                                    }) : 'N/A';
                                    $('#assignments-table2 tbody').append(`
                <tr>
                    <td data-toggle="tooltip" title="ID: ${assignment.id}">${assignment.id}</td>
                    <td data-toggle="tooltip" title="Topic: ${assignment.topic}">${assignment.topic.slice(0, 10)}${assignment.topic.length > 10 ? '...' : ''}</td>
                    <td data-toggle="tooltip" title="Desc: ${assignment.description || 'N/A'}">${(assignment.description || 'N/A').slice(0, 10)}${(assignment.description || '').length > 10 ? '...' : ''}</td>
                    <td data-toggle="tooltip" title="Max Mks: ${assignment.max_marks}">${assignment.max_marks}</td>
                    <td data-toggle="tooltip" title="Batch: ${assignment.batch_no}">${assignment.batch_no}</td>
                    <td data-toggle="tooltip" title="Deadline: ${deadline}">${deadline.slice(0, 8)}${deadline.length > 8 ? '...' : ''}</td>
                    <td data-toggle="tooltip" title="Type: ${assignment.type || 'N/A'}">${(assignment.type || 'N/A').slice(0, 5)}${(assignment.type || '').length > 5 ? '...' : ''}</td>
                    <td data-toggle="tooltip" title="File: ${assignment.file || 'N/A'}">${displayFile(assignment.file)}</td>
                    <td data-toggle="tooltip" title="Created: ${createdAt}">${createdAt.slice(0, 8)}${createdAt.length > 8 ? '...' : ''}</td>
                    <td class="action-cell">
                        <button class="btn btn-outline-purple btn-sm me-1 view-btn action-btn" data-id="${assignment.id}" data-bs-toggle="modal" data-bs-target="#editAssignmentModal"><i class="bi bi-eye"></i></button>
                        <button class="btn btn-outline-purple btn-sm me-1 edit-btn action-btn" data-id="${assignment.id}"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-outline-danger btn-sm delete-btn2 action-btn" data-id="${assignment.id}"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            `);
                                });

                                // Initialize tooltips
                                $('[data-toggle="tooltip"]').tooltip({
                                    placement: 'top',
                                    trigger: 'hover',
                                    html: true
                                });

                                // Attach event handlers for actions
                                $('.edit-btn').on('click', function() {
                                    const id = $(this).data('id');
                                    const assignment = response.find(a => a.id === id);
                                    if (assignment) {
                                        $('#edit-assignment-id').val(assignment.id);
                                        $('#edit-topic').val(assignment.topic);
                                        $('#edit-description').val(assignment.description || '');
                                        $('#edit-max-marks').val(assignment.max_marks);
                                        $('#edit-batch-no').val(assignment.batch_no.toString());
                                        $('#edit-deadline').val(assignment.deadline ? new Date(
                                                assignment.deadline).toISOString().slice(0,
                                            16) : '');
                                        $('#edit-type').val(assignment.type || '');
                                        $('#edit-file-current').val(assignment.file || '');
                                        $('#edit-file-display').html(displayFile(assignment.file));
                                        $('#edit-file').val('');
                                        $('#editAssignmentModal').modal('show');
                                    }
                                });

                                $('.view-btn').on('click', function() {
                                    const id = $(this).data('id');
                                    const assignment = response.find(a => a.id === id);
                                    if (assignment) {
                                        $('#edit-assignment-id').val(assignment.id);
                                        $('#edit-topic').val(assignment.topic).prop('readonly',
                                            true);
                                        $('#edit-description').val(assignment.description || '')
                                            .prop('readonly', true);
                                        $('#edit-max-marks').val(assignment.max_marks).prop(
                                            'readonly', true);
                                        $('#edit-batch-no').val(assignment.batch_no.toString())
                                            .prop('disabled', true);
                                        $('#edit-deadline').val(assignment.deadline ? new Date(
                                                assignment.deadline).toISOString().slice(0,
                                            16) : '').prop('readonly', true);
                                        $('#edit-type').val(assignment.type || '').prop('readonly',
                                            true);
                                        $('#edit-file-current').val(assignment.file || '');
                                        $('#edit-file-display').html(displayFile(assignment.file));
                                        $('#edit-file').prop('disabled', true).prop('readonly',
                                            false);
                                        $('#editAssignmentModal').modal('show');
                                    }
                                });
                            } else {
                                $('#assignments-table2 tbody').html(
                                    '<tr><td colspan="10" class="text-center">No assignments</td></tr>');
                            }
                        },
                        error: function(xhr) {
                            $('.loader-table-teacher').hide();
                            $('#assignments-table2 tbody').html(
                                '<tr><td colspan="10" class="text-center">Error loading</td></tr>');
                            console.error('Error:', xhr.responseText);
                        }
                        // Save edited assignment
                        $('#saveAssignmentBtn').on('click', function() {
                            const button = $(this);
                            button.prop('disabled', true);
                            button.find('.btn-text').addClass('d-none');
                            button.find('.btn-spinner').removeClass('d-none');

                            const id = $('#edit-assignment-id').val();
                            const formData = new FormData();
                            formData.append('assignment_id', id);
                            formData.append('topic', $('#edit-topic').val());
                            formData.append('description', $('#edit-description').val());
                            formData.append('max_marks', $('#edit-max-marks').val());
                            formData.append('batch_no', $('#edit-batch-no').val());
                            formData.append('deadline', $('#edit-deadline').val());
                            formData.append('type', $('#edit-type').val());
                            formData.append('file', $('#edit-file')[0].files[
                            0]); // Append new file if selected
                            formData.append('current_file', $('#edit-file-current')
                        .val()); // Send current file path
                            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                            $.ajax({
                                url: `/dashboard/teacher/edit-assignment/${user_id}`,
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(response) {
                                    $('#editAssignmentModal').modal('hide');
                                    fetchAssignments();
                                    showToast('Assignment Updated Successfully', 'success');
                                },
                                error: function(xhr) {
                                    alert('Error updating assignment: ' + (xhr.responseJSON
                                        ?.message || 'Unknown error'));
                                },
                                complete: function() {
                                    button.prop('disabled', false);
                                    button.find('.btn-text').removeClass('d-none');
                                    button.find('.btn-spinner').addClass('d-none');
                                }
                            });
                        });

                        $('#assignments-table2').on('click', '.delete-btn2', function() {
                            const id = $(this).data('id');
                            if (confirm('Are you sure you want to delete this assignment?')) {
                                $.ajax({
                                    url: `/dashboard/teacher/delete-assignment/${user_id}`,
                                    type: 'POST',
                                    data: {
                                        id,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        $(`#assignments-table2 tbody tr`).filter(function() {
                                            return $(this).find('.delete-btn2').data(
                                                'id') === id;
                                        }).remove();
                                        showToast('Assignment deleted successfully!',
                                        'success');
                                    },
                                    error: function(xhr) {
                                        alert('Error deleting assignment: ' + (xhr.responseJSON
                                            ?.message || 'Unknown error'));
                                    }
                                });
                            }
                        });

                        function showToast(message, type = 'success') {
                            const toastId = 'toast-' + Date.now();
                            const iconClass = type === 'success' ? 'bi-check-circle-fill text-success' :
                                type === 'error' ? 'bi-x-circle-fill text-danger' :
                                'bi-exclamation-triangle-fill text-warning';

                            const bgClass = type === 'success' ? 'bg-success' :
                                type === 'error' ? 'bg-danger' : 'bg-warning';

                            const toast = `
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
                <div class="toast-header ${bgClass} text-white">
                    <i class="bi ${iconClass} me-2"></i>
                    <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

                            $('.toast-container').append(toast);
                            const toastElement = new bootstrap.Toast(document.getElementById(toastId));
                            toastElement.show();

                            setTimeout(() => {
                                $('#' + toastId).remove();
                            }, 5000);
                        }
                    });
    </script>



    <style>
    :root {
        --purple: #8338EB;
        --purple-dark: #6d2bc7;
        --purple-light: rgba(131, 56, 235, 0.1);
        --purple-medium: rgba(131, 56, 235, 0.2);
        --purple-gradient: linear-gradient(135deg, #8338EB 0%, #6d2bc7 100%);
        --shadow-light: 0 2px 10px rgba(131, 56, 235, 0.1);
        --shadow-medium: 0 4px 20px rgba(131, 56, 235, 0.15);
        --shadow-strong: 0 8px 30px rgba(131, 56, 235, 0.2);
        --border-radius: 12px;
        --border-radius-small: 8px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Enhanced Typography */
    .header-title {
        background: var(--purple-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 1.75rem;
        text-shadow: 0 2px 4px rgba(131, 56, 235, 0.1);
        animation: fadeInUp 0.6s ease-out;
    }

    /* Search Container */
    .search-container {
        position: relative;
        animation: fadeInRight 0.6s ease-out;
    }

    .search-input {
        border-radius: var(--border-radius-small);
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: var(--transition);
        box-shadow: var(--shadow-light);
        border: 2px solid rgba(131, 56, 235, 0.2) !important;
    }

    .search-input:focus {
        border-color: var(--purple) !important;
        box-shadow: 0 0 0 0.2rem rgba(131, 56, 235, 0.25), var(--shadow-medium);
        transform: translateY(-1px);
    }

    .search-icon {
        border-radius: var(--border-radius-small) 0 0 var(--border-radius-small);
        border: 2px solid rgba(131, 56, 235, 0.2) !important;
        border-right: none !important;
        background: linear-gradient(135deg, rgba(131, 56, 235, 0.05) 0%, rgba(131, 56, 235, 0.1) 100%) !important;
    }

    /* Table Container */
    .table-container {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-medium);
        overflow: hidden;
        animation: fadeInUp 0.8s ease-out;
        border: 1px solid rgba(131, 56, 235, 0.1);
    }

    .modern-table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table-header th {
        color: var(--purple);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 12px;
        border: none;
        position: relative;
    }

    .table-header th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--purple-gradient);
    }

    .modern-table tbody tr {
        transition: var(--transition);
        border: none;
    }

    /* .modern-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(131, 56, 235, 0.02) 0%, rgba(131, 56, 235, 0.05) 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(131, 56, 235, 0.1);
    } */

    .modern-table tbody td {
        padding: 5px 12px;
        vertical-align: middle;
        border-top: 1px solid rgba(131, 56, 235, 0.1);
    }

    /* Action Buttons */
    .action-cell {
        text-align: center;
        white-space: nowrap;
    }

    .action-btn {
        padding: 8px 12px;
        border-radius: var(--border-radius-small);
        transition: var(--transition);
        font-size: 0.85rem;
        margin: 0 2px;
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .action-btn:hover::before {
        left: 100%;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .btn-outline-purple:hover {
        background: var(--purple-gradient) !important;
        border-color: var(--purple) !important;
        color: white !important;
    }

    .btn-outline-danger:hover {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    /* Modal Enhancements */
    .modern-modal {
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-strong);
    }

    .modal-header-gradient {
        background: var(--purple-gradient) !important;
        border: none;
        position: relative;
    }

    .modal-header-gradient::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body-enhanced {
        padding: 2rem;
        background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%);
    }

    .form-group-enhanced {
        position: relative;
    }

    .form-control-enhanced,
    .form-select-enhanced {
        border-radius: var(--border-radius-small);
        border: 2px solid rgba(131, 56, 235, 0.2);
        padding: 12px 16px;
        transition: var(--transition);
        background: white;
        font-size: 0.95rem;
    }

    .form-control-enhanced:focus,
    .form-select-enhanced:focus {
        border-color: var(--purple);
        box-shadow: 0 0 0 0.2rem rgba(131, 56, 235, 0.25);
        transform: translateY(-1px);
        background: white;
    }

    .form-label {
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .modal-footer-enhanced {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border: none;
        border-top: 1px solid rgba(131, 56, 235, 0.1);
    }

    .btn-save-enhanced {
        background: var(--purple-gradient);
        border: none;
        padding: 12px 24px;
        border-radius: var(--border-radius-small);
        font-weight: 600;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-save-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .btn-cancel {
        padding: 12px 24px;
        border-radius: var(--border-radius-small);
        font-weight: 600;
        transition: var(--transition);
        border: 2px solid #6c757d;
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        background: #6c757d;
        box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }

    /* Utility Classes */
    .text-purple {
        color: var(--purple) !important;
    }

    .border-purple {
        border-color: var(--purple) !important;
    }

    .bg-purple {
        background: var(--purple-gradient) !important;
    }

    .btn-purple {
        background: var(--purple-gradient);
        border: none;
        color: white;
        transition: var(--transition);
    }

    .btn-purple:hover {
        background: linear-gradient(135deg, #6d2bc7 0%, #5a249a 100%);
        transform: translateY(-1px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .btn-outline-purple {
        border: 2px solid var(--purple);
        color: var(--purple);
        background: transparent;
        transition: var(--transition);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .container-fluid {
            padding: 1rem 2rem;
        }
    }

    @media (max-width: 992px) {
        .header-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .search-container {
            margin-top: 1rem;
        }

        .table-container {
            margin-top: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .header-title {
            font-size: 1rem;
            text-align: center;
        }

        .modern-table {
            font-size: 0.8rem;
        }

        .table-header th {
            padding: 6px 8px;
            color: black;
            font-size: 0.75rem;
        }

        .modern-table tbody td {
            padding: 10px 8px;
        }

        .action-btn {
            padding: 6px 8px;
            font-size: 0.75rem;
            margin: 0 1px;
        }

        .modal-body-enhanced {
            padding: 1.5rem;
        }

        .modal-footer-enhanced {
            padding: 1rem 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .header-title {
            font-size: 1.2rem;
        }

        .modern-table {
            font-size: 0.75rem;
        }

        .table-header th {
            padding: 10px 6px;
            font-size: 0.7rem;
        }

        .modern-table tbody td {
            padding: 8px 6px;
        }

        .action-btn {
            padding: 4px 6px;
            font-size: 0.7rem;
        }

        .action-btn i {
            font-size: 0.8rem;
        }

        .search-input {
            padding: 10px 14px;
            font-size: 0.9rem;
        }
    }

    /* Custom scrollbar for table */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--purple-gradient);
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #6d2bc7 0%, #5a249a 100%);
    }

    /* Loading states */
    .btn-spinner .spinner-border {
        width: 1rem;
        height: 1rem;
    }

    /* Enhanced focus states for accessibility */
    .form-control:focus,
    .form-select:focus,
    .btn:focus {
        outline: 2px solid var(--purple);
        outline-offset: 2px;
    }

    /* Smooth page transitions */
    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        min-height: 100vh;
    }

    .table>:not(caption)>*>* {
        border-bottom-width: 0 !important;
    }
    </style>
</x-teacher-dashboard-layout>
