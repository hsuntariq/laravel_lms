<x-teacher-dashboard-layout>
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <x-toast />
    <section style="height:89vh;overflow-y:scroll;">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-capitalize">{{ request()->segment(3) }}</h5>
            <form action="" class="d-flex gap-3 my-2 teacher-form">
                <select name="teacher-course_name_teacher" id="teacher-course-select"
                    class="form-select form-select-sm border-purple text-purple fw-medium bg-transparent teacher-course-select"></select>
                <select name="teacher-batch_no" id="teacher-batch-select"
                    class="form-select form-select-sm border-purple text-purple fw-medium bg-transparent teacher-batch-select"></select>
            </form>
        </div>

        <section class="filter justify-content-between my-2 d-flex align-items-center teacher-filter">
            <form action="" method="POST"
                class="form-control form-control-sm p-0 px-3 d-flex align-items-center rounded-pill gap-2 w-25 teacher-search-form">
                <i class="bi bi-search" style="color:#8338EB"></i>
                <input type="text" class="border-0 w-100 input-search teacher-search-input" style="outline-width:0"
                    placeholder="Search by student, topic, or deadline...">
            </form>
            <ul
                class="d-flex m-0 align-items-center list-unstyled text-purple fw-semibold text-sm gap-2 teacher-filter-list">
                <li><button type="button" class="filter-button btn text-sm fw-semibold teacher-filter-btn active"
                        data-filter="all">All</button></li>
                <li><button type="button" class="filter-button btn text-sm fw-semibold teacher-filter-btn"
                        data-filter="marked">Marked</button></li>
                <li><button type="button" class="filter-button btn text-sm fw-semibold teacher-filter-btn"
                        data-filter="unmarked">Unmarked</button></li>
            </ul>
        </section>

        <section class="row justify-content-between teacher-charts-row">
            <div class="col-sm-4" style="height:200px">{!! $pieChart->container() !!}</div>
            <div class="col-sm-4" style="height:200px">{!! $doughnetChart->container() !!}</div>
            <div class="col-sm-4" style="height:200px">{!! $lineChart->container() !!}</div>
        </section>

        <div class="table-responsive teacher-table-responsive">
            @include('teacher.partials.table-loader', ['class' => 'teacher-loader'])
            <div class="position-relative">
                <div class="loading-overlay d-none" id="table-loading-overlay">
                    <div class="spinner-border text-purple" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <table
                    class="table text-capitalize table-sm table-bordered table-striped text-sm btn-sm input-group-sm teacher-table-container">
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Day</td>
                            <td>Student</td>
                            <td>Topic</td>
                            <td>Batch</td>
                            <td>Submit Time</td>
                            <td>File</td>
                            <td>Max Marks</td>
                            <td>Mark & Comment</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody class='teacher-submitted-assignments'></tbody>
                </table>
            </div>
        </div>
    </section>

    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
    {!! $lineChart->script() !!}
    <x-jquery />

    <!-- Enhanced Modal for Mark & Comment -->
    <div class="modal fade" id="marksCommentModal" tabindex="-1" aria-labelledby="marksCommentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-purple" id="marksCommentModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>
                        <span id="modal-title-text">Mark Assignment & Add Comment</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Assignment Details Section -->
                    <div class="assignment-details mb-4" id="assignment-details">
                        <h6 class="text-purple mb-3"><i class="bi bi-info-circle me-2"></i>Assignment Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Student:</span>
                                    <span class="detail-value ms-2" id="modal-student-name">-</span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Topic:</span>
                                    <span class="detail-value ms-2" id="modal-topic">-</span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Batch:</span>
                                    <span class="detail-value ms-2" id="modal-batch">-</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Course:</span>
                                    <span class="detail-value ms-2" id="modal-course">-</span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Deadline:</span>
                                    <span class="detail-value ms-2" id="modal-deadline">-</span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Max Marks:</span>
                                    <span class="detail-value ms-2" id="modal-max-marks-display">-</span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label fw-bold text-muted">Submitted:</span>
                                    <span class="detail-value ms-2" id="modal-submit-time">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden form fields -->
                    <input type="hidden" id="currentAnswerId">
                    <input type="hidden" id="modalAssignmentId">
                    <input type="hidden" id="modalUserId">
                    <input type="hidden" id="modalMaxMarks">
                    <input type="hidden" id="modalBatchNo">
                    <input type="hidden" id="modalCourseNo">

                    <!-- Marking Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="modalObtMarksInput" class="form-label">
                                    <i class="bi bi-award me-1"></i>Obtained Marks
                                </label>
                                <input type="number" class="form-control" id="modalObtMarksInput" min="0"
                                    placeholder="Enter obtained marks">
                                <div class="form-text">Maximum marks: <span id="max-marks-hint" class="fw-bold">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-graph-up me-1"></i>Grade
                                </label>
                                <div class="form-control bg-light fw-bold text-center" id="grade-display">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modalCommentInput" class="form-label">
                            <i class="bi bi-chat-square-text me-1"></i>Comment
                        </label>
                        <textarea id="modalCommentInput" class="form-control" rows="4"
                            placeholder="Write your feedback here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="saveMarkCommentBtn" class="btn btn-purple">
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

    <style>
    .teacher-error-marks,
    .teacher-small-ph {
        padding: 4px;
        display: flex;
        align-items: center;
    }

    .form-control-sm {
        font-size: 0.8rem;
        padding: 4px 8px;
    }

    .filter-button.active {
        background-color: #8338EB !important;
        color: white !important;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .assignment-details {
        background: rgba(131, 56, 235, 0.1);
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid #8338EB;
    }

    .search-highlight {
        background-color: #fff3cd;
        padding: 1px 3px;
        border-radius: 3px;
        font-weight: bold;
    }

    .btn-purple {
        background-color: #8338EB;
        border-color: #8338EB;
        color: white;
    }

    .btn-purple:hover {
        background-color: #6d2bc7;
        border-color: #6d2bc7;
        color: white;
    }

    .text-purple {
        color: #8338EB !important;
    }

    .border-purple {
        border-color: #8338EB !important;
    }
    </style>

    <script>
    // Toast function
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

    // Search functionality
    function highlightText(text, searchTerm) {
        if (!searchTerm || !text) return text;
        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
        return text.toString().replace(regex, '<span class="search-highlight">$1</span>');
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Grade calculation
    function calculateGrade(obtMarks, maxMarks) {
        const percentage = (obtMarks / maxMarks) * 100;
        if (percentage >= 90) return 'A+';
        if (percentage >= 80) return 'A';
        if (percentage >= 70) return 'B';
        if (percentage >= 60) return 'C';
        if (percentage >= 50) return 'D';
        return 'F';
    }

    function teacherDisplayFile(file) {
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
        return `<a href='${fileUrl}' download>
            <img width='30px' height='30px' src='${fileIcons[ext] || fileIcons.default}' alt='file icon'>
        </a>`;
    }

    $(document).ready(function() {
        const pathSegments = window.location.pathname.split("/");
        const user_id = pathSegments[pathSegments.length - 1];
        const isTeacher = pathSegments.includes("teacher");
        const isAssignmentView = isTeacher && pathSegments.includes("assignments") && pathSegments.includes(
            "view");

        // Bootstrap 5 modal instance
        const marksCommentModalEl = document.getElementById('marksCommentModal');
        const marksCommentModal = new bootstrap.Modal(marksCommentModalEl);

        let allAssignments = [];
        let filteredAssignments = [];
        let currentSearchTerm = '';

        if (isTeacher) {
            $.ajax({
                url: `/dashboard/teacher/get-relevent-batches/${user_id}`,
                type: "GET",
                beforeSend: function() {
                    $('select[name="teacher-batch_no"]').html(
                        `<option disabled selected>Loading Batches...</option>`);
                    $('select[name="teacher-course_name_teacher"]').html(
                        `<option disabled selected>Loading Courses...</option>`);
                },
                success: function(response) {
                    const batchOptions = `<option selected disabled>Select Batch</option>` +
                        response?.batches?.map(item =>
                            `<option value="${item?.id}">Batch ${item.batch_no}</option>`).join("");
                    const courseOptions = `<option selected disabled>Select Course</option>` +
                        response?.courses?.map(item =>
                            `<option value="${item?.id}">${item?.course_name}</option>`).join("");
                    $('select[name="teacher-batch_no"]').html(batchOptions);
                    $('select[name="teacher-course_name_teacher"]').html(courseOptions);
                },
                error: function() {
                    showToast("Error loading batches and courses", 'error');
                }
            });
        }

        if (isAssignmentView) {
            setTimeout(() => {
                const firstBatchId = $('select[name="teacher-batch_no"]').find("option:eq(1)").val();
                if (firstBatchId) teacherGetSubmittedAssignments(firstBatchId);
            }, 1000);

            $('select[name="teacher-batch_no"]').on("change", function() {
                teacherGetSubmittedAssignments($(this).val());
            });

            function teacherGetSubmittedAssignments(batch_no) {
                $(".teacher-loader").show();
                $("#table-loading-overlay").removeClass('d-none');

                $.ajax({
                    url: `/dashboard/teacher/submitted-assignment/${user_id}`,
                    dataType: "json",
                    type: "GET",
                    data: {
                        batch_no
                    },
                    success: function(response) {
                        allAssignments = response;
                        filteredAssignments = allAssignments;
                        teacherDisplayAssignments(filteredAssignments);
                        $(".teacher-loader").hide();
                        $("#table-loading-overlay").addClass('d-none');
                    },
                    error: function() {
                        showToast("Error loading assignments", 'error');
                        $(".teacher-loader").hide();
                        $("#table-loading-overlay").addClass('d-none');
                    }
                });
            }

            function getDay(day) {
                return ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][day] ||
                    "";
            }

            function teacherDisplayAssignments(assignments) {
                const rows = assignments.map((assignment) => {
                    const createdAt = new Date(assignment.assignment?.created_at);
                    const day = getDay(createdAt.getDay());
                    const formattedDate = createdAt.toLocaleString("en-US", {
                        timeZone: "Asia/Karachi",
                        month: "2-digit",
                        day: "2-digit",
                        hour: "2-digit",
                        minute: "2-digit"
                    });
                    const studentName = assignment?.user?.name || "N/A";
                    const topic = assignment?.assignment?.topic || "N/A";
                    const batch = assignment?.assignment?.batch_no || "N/A";
                    const submitTime = assignment?.assignment?.deadline || "N/A";
                    const maxMarks = assignment?.assignment?.max_marks || "N/A";

                    // Apply search highlighting
                    const highlightedStudent = highlightText(studentName, currentSearchTerm);
                    const highlightedTopic = highlightText(topic, currentSearchTerm);
                    const highlightedDeadline = highlightText(submitTime, currentSearchTerm);

                    const answerFile = assignment?.answer_file ? teacherDisplayFile(assignment
                        .answer_file) : "No file";
                    const isMarked = assignment?.marks !== null;
                    const obtainedMarks = isMarked ? (assignment.marks?.obt_marks || "0") : "-";
                    const commentText = assignment.marks?.comments || "";

                    const markCommentButton = `<button
                        class="btn btn-purple btn-sm open-marks-comment-modal"
                        data-bs-toggle="modal"
                        data-answer_id="${assignment.id}"
                        data-assignment_id="${assignment.assignment_id}"
                        data-user_id="${assignment?.user?.id}"
                        data-user_name="${studentName}"
                        data-topic="${topic}"
                        data-course_name="${assignment?.assignment?.course_name || 'N/A'}"
                        data-deadline="${submitTime}"
                        data-max_marks="${assignment.assignment?.max_marks}"
                        data-batch_no="${assignment.user?.batch_assigned}"
                        data-course_no="${assignment.user?.course_assigned}"
                        data-obt_marks="${isMarked ? obtainedMarks : ''}"
                        data-comment="${commentText}"
                        data-submit_time="${formattedDate}"
                    >
                        <i class="bi ${isMarked ? 'bi-pencil' : 'bi-plus-circle'} me-1"></i>
                        ${isMarked ? "Edit" : "Mark"}
                    </button>`;

                    return `
                        <tr data-assignment-id="${assignment.id}">
                            <td>${formattedDate}</td>
                            <td>${day}</td>
                            <td>${highlightedStudent}</td>
                            <td>${highlightedTopic}</td>
                            <td>Batch ${batch}</td>
                            <td>${highlightedDeadline}</td>
                            <td>${answerFile}</td>
                            <td>${maxMarks}</td>
                            <td class="marks-comment-cell">
                                <div><strong>Marks:</strong> <span class="marks-display">${obtainedMarks}</span></div>
                                <div><strong>Comment:</strong> <span class="comment-display">${commentText || '-'}</span></div>
                            </td>
                            <td>${markCommentButton}</td>
                        </tr>`;
                }).join("");

                $(".teacher-submitted-assignments").html(rows);
                attachModalHandlers();
            }

            function attachModalHandlers() {
                $(".open-marks-comment-modal").off('click').on("click", function() {
                    const button = $(this);
                    const isEdit = button.text().trim().includes('Edit');

                    $("#modal-title-text").text(isEdit ? "Edit Assignment Marks & Comment" :
                        "Mark Assignment & Add Comment");

                    $("#currentAnswerId").val(button.data("answer_id"));
                    $("#modalObtMarksInput").val(button.data("obt_marks") || "");
                    $("#modalCommentInput").val(button.data("comment") || "");

                    $("#modalAssignmentId").val(button.data("assignment_id"));
                    $("#modalUserId").val(button.data("user_id"));
                    $("#modalMaxMarks").val(button.data("max_marks"));
                    $("#modalBatchNo").val(button.data("batch_no"));
                    $("#modalCourseNo").val(button.data("course_no"));

                    // Fill assignment details
                    $("#modal-student-name").text(button.data("user_name"));
                    $("#modal-topic").text(button.data("topic"));
                    $("#modal-batch").text("Batch " + button.data("batch_no"));
                    $("#modal-course").text(button.data("course_name"));
                    $("#modal-deadline").text(button.data("deadline"));
                    $("#modal-max-marks-display").text(button.data("max_marks"));
                    $("#modal-submit-time").text(button.data("submit_time"));
                    $("#max-marks-hint").text(button.data("max_marks"));

                    // Update grade display
                    const obtMarks = button.data("obt_marks");
                    const maxMarks = button.data("max_marks");
                    if (obtMarks && maxMarks) {
                        const grade = calculateGrade(obtMarks, maxMarks);
                        $("#grade-display").text(grade);
                    } else {
                        $("#grade-display").text("-");
                    }

                    marksCommentModal.show();
                });
            }

            // Grade calculation on input
            $("#modalObtMarksInput").on('input', function() {
                const obtMarks = parseFloat($(this).val()) || 0;
                const maxMarks = parseFloat($("#modalMaxMarks").val()) || 0;

                if (obtMarks && maxMarks) {
                    const grade = calculateGrade(obtMarks, maxMarks);
                    $("#grade-display").text(grade);
                } else {
                    $("#grade-display").text("-");
                }
            });

            $("#saveMarkCommentBtn").on("click", function() {
                const button = $(this);
                const answerId = $("#currentAnswerId").val();
                const obtMarks = $("#modalObtMarksInput").val();
                const comment = $("#modalCommentInput").val();
                const assignmentId = $("#modalAssignmentId").val();
                const userId = $("#modalUserId").val();
                const maxMarks = $("#modalMaxMarks").val();
                const batchNo = $("#modalBatchNo").val();
                const courseNo = $("#modalCourseNo").val();

                if (!obtMarks || obtMarks < 0 || parseFloat(obtMarks) > parseFloat(maxMarks)) {
                    showToast(`Please enter valid obtained marks (0-${maxMarks}).`, 'error');
                    return;
                }

                // Show loading state
                button.prop('disabled', true);
                button.find('.btn-text').addClass('d-none');
                button.find('.btn-spinner').removeClass('d-none');

                $.ajax({
                    url: `/dashboard/teacher/mark-assignment/${user_id}`,
                    type: "POST",
                    dataType: "json",
                    data: {
                        answer_id: answerId,
                        obt_marks: obtMarks,
                        comments: comment,
                        assignment_id: assignmentId,
                        user_id: userId,
                        max_marks: maxMarks,
                        batch_no: batchNo,
                        course_no: courseNo,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        marksCommentModal.hide();

                        // Update the row in the table
                        const row = $(`tr[data-assignment-id='${answerId}']`);
                        const marksCell = row.find(".marks-comment-cell");

                        marksCell.html(`
                            <div><strong>Marks:</strong> <span class="marks-display">${obtMarks}</span></div>
                            <div><strong>Comment:</strong> <span class="comment-display">${comment || '-'}</span></div>
                        `);

                        // Update button
                        const actionButton = row.find("button.open-marks-comment-modal");
                        actionButton.html('<i class="bi bi-pencil me-1"></i>Edit');
                        actionButton.data("obt_marks", obtMarks);
                        actionButton.data("comment", comment);

                        showToast("Marks and comment saved successfully!", 'success');
                        attachModalHandlers();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            "Error while saving marks";
                        showToast(errorMessage, 'error');
                    },
                    complete: function() {
                        // Reset button state
                        button.prop('disabled', false);
                        button.find('.btn-text').removeClass('d-none');
                        button.find('.btn-spinner').addClass('d-none');
                    }
                });
            });

            // Search functionality
            $(".teacher-search-input").on('input', function() {
                currentSearchTerm = $(this).val().trim();
                applyFiltersAndSearch();
            });

            function applyFiltersAndSearch() {
                const currentFilter = $(".teacher-filter-btn.active").data('filter');

                let assignmentsToShow = allAssignments;

                // Apply filter first
                if (currentFilter === "marked") {
                    assignmentsToShow = allAssignments.filter(a => a.marks !== null);
                } else if (currentFilter === "unmarked") {
                    assignmentsToShow = allAssignments.filter(a => a.marks === null);
                }

                // Then apply search
                if (currentSearchTerm) {
                    assignmentsToShow = assignmentsToShow.filter(assignment => {
                        const studentName = assignment.user?.name?.toLowerCase() || '';
                        const topic = assignment.assignment?.topic?.toLowerCase() || '';
                        const deadline = assignment.assignment?.deadline?.toLowerCase() || '';
                        const searchLower = currentSearchTerm.toLowerCase();

                        return studentName.includes(searchLower) ||
                            topic.includes(searchLower) ||
                            deadline.includes(searchLower);
                    });
                }

                filteredAssignments = assignmentsToShow;
                teacherDisplayAssignments(filteredAssignments);
            }

            // Filter buttons functionality
            $(".teacher-filter-btn").on("click", function() {
                $(".teacher-filter-btn").removeClass("active");
                $(this).addClass("active");
                applyFiltersAndSearch();
            });
        }
    });
    </script>
</x-teacher-dashboard-layout>
