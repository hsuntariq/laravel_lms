// User image update preview
$(".image-preview").css("display", "none");

$(".update-image").on("input", function (e) {
    const imageUrl = URL.createObjectURL(e.target.files[0]);
    $(".image-preview").css("display", "block").attr("src", imageUrl);
});

// Add assignment
$(".loading").hide();
$(".count-loading").hide();
$(".flash").hide();

$(".add-assignment").click(function (e) {
    e.preventDefault();
    $(".loading").show();
    $(".loading-text").hide();

    $.ajax({
        url: "/dashboard/teacher/upload-assignment",
        type: "POST",
        data: new FormData($(".assignment-data")[0]),
        processData: false,
        contentType: false,

        beforeSend: function () {
            $(".text-danger").remove(); // Clear previous errors
        },

        success: function (response) {
            $(".assignment-data")[0].reset();
            showFlashMessage(response.message);
            countAssignments();
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                displayValidationErrors(xhr.responseJSON.errors);
            }
        },

        complete: function () {
            $(".loading").hide();
            $(".loading-text").show();
        },
    });
});

// Function to count assignments
function countAssignments() {
    const batch_no = $('[name="batch_no"]').val() || $('[name="batch_no"]').find("option:first").val();
    $(".count-loading").show();
    $(".total-assignments, .total-tests").hide();

    $.ajax({
        url: "/dashboard/teacher/assignment-count",
        type: "GET",
        data: { batch_no: batch_no },
        success: function (response) {
            $(".total-assignments").html(response.assignments);
            $(".total-tests").html(response.tests);
        },

        error: function (xhr) {
            // Handle error appropriately
            console.error("Error fetching assignment count:", xhr.statusText);
        },

        complete: function () {
            $(".count-loading").hide();
            $(".total-assignments, .total-tests").show();
        },
    });
}

// Count assignments on page load
$(document).ready(function () {
    if (window.location.pathname === '/dashboard/teacher/assignments/upload') {
        countAssignments();
        $('[name="batch_no"]').on("input", countAssignments);
    }
});

// Get assignments
function getAssignments() {
    const batch_no = window.location.pathname.split("/").pop();
    $(".loader-table").show();
    $(".assignment-table").hide();

    $.ajax({
        url: `/dashboard/student/assignments-get/${batch_no}`,
        type: "GET",
        success: function (response) {
            let assignmentsHtml = response.map((assignment, index) => {
                const createdAtDate = new Date(assignment.created_at);
                const options = {
                    timeZone: "Asia/Karachi",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                };
                const formattedCreatedAt = createdAtDate.toLocaleString("en-US", options);

                return `
                <tr>
                    <td class="text-sm">${index + 1}</td>
                    <td class="text-sm">${assignment.topic}</td>
                    <td class="text-sm">${assignment.max_marks}</td>
                    <td class="text-sm">${formattedCreatedAt}</td>
                    <td class="text-sm">${formattedCreatedAt}</td>
                    ${assignment?.answers?.length > 0 ?
                        `<td colspan="4" class="text-center">
                            <i class="bi bi-check-circle-fill text-success"></i> Submitted
                        </td>` :
                        `<td class="text-sm">pending...</td>
                        <td class="text-sm">
                            <form class="upload-form" enctype="multipart/form-data">
                                <input name="assignment_id" type="hidden" value="${assignment.id}">
                                <input name="user_id" type="hidden" value="3">
                                <div class="input-group input-group-sm">
                                    <input name="answer_file" type="file" class="form-control file-input">
                                    <div class="error-message text-danger" style="display: none;"></div>
                                </div>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-purple border-0 btn-disabled p-1 px-2 submit-btn" disabled>
                                <img class="loading-submit d-none" src="loading.gif" width="20px" alt="loading">
                                <span>Submit</span>
                            </button>
                        </td>`
                    }
                </tr>`;
            }).join('');

            $("#assignmentsTableBody").html(assignmentsHtml);
        },

        error: function (xhr) {
            // Handle error appropriately
            console.error("Error fetching assignments:", xhr.statusText);
        },

        complete: function () {
            $(".loader-table").hide();
            $(".assignment-table").show();
        },
    });
}

// Call the function to load assignments
getAssignments();

// Attach event listeners to file inputs and submit buttons
$(document).on("change", ".file-input", function () {
    const submitBtn = $(this).closest("tr").find(".submit-btn");
    if ($(this).val()) {
        submitBtn.removeAttr("disabled").removeClass("btn-disabled");
    } else {
        submitBtn.addClass("btn-disabled").attr("disabled", "disabled");
    }
});

// Handle assignment upload
$(document).on("click", ".submit-btn", function (e) {
    e.preventDefault();
    const form = $(this).closest("tr").find(".upload-form");
    const input = $(this).closest("tr").find(".file-input");
    const row = $(this).closest("tr");
    const loader = $(this).closest("tr").find(".loading-submit");
    addUploadAssignment(form, input, row, loader);
});

// Add assignment upload function
function addUploadAssignment(form, input, row, loader) {
    const formData = new FormData(form[0]);
    const errorMessageDiv = form.find(".error-message");
    loader.removeClass("d-none");
    errorMessageDiv.hide().text("");

    $.ajax({
        url: `/dashboard/student/upload-assignment`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === "success") {
                updateRowAfterSubmission(row);
                showFlashMessage(response.message);
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                displayValidationErrors(xhr.responseJSON.errors, errorMessageDiv);
            } else {
                // Handle error appropriately
                showFlashMessage("Failed to upload the assignment", "error");
            }
        },
        complete: function () {
            loader.addClass("d-none");
        },
    });
}

// Function to update the row after submission
function updateRowAfterSubmission(row) {
    row.find("td:nth-child(6)").remove(); // Remove 'pending...'
    row.find("td:nth-child(6)").remove(); // Remove form column
    row.find("td:nth-child(6)").remove(); // Remove submit button column
    row.append(`
        <td colspan="3" class="text-center">
            <i class="bi bi-check-circle-fill text-success"></i> Submitted
        </td>
    `);
}

// Function to display flash messages
function showFlashMessage(message, type = "success") {
    $(".flash").show();
    $(".notificationPara").html(message);
    $(".flash").addClass(type === "success" ? "alert-success" : "alert-danger");
    $(".AllowBtn").off("click").on("click", function () {
        $(".flash").fadeOut();
    });
}

// Function to display validation errors
function displayValidationErrors(errors, errorMessageDiv) {
    $.each(errors, function (key, value) {
        const errorElement = `<p class="text-danger fw-medium m-0">${value[0]}</p>`;
        $(`input[name="${key}"], select[name="${key}"]`).after(errorElement);
    });
    errorMessageDiv.show();
}

// Get the related day
function getDay(day) {
    const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    return days[day] || '';
}

// Get assignment format
function displayFile(file) {
    const ext = file?.split(".").pop();
    const fileUrl = `/storage/${file}`;
    const fileIcons = {
        html: "https://example.com/html-icon.png",
        png: "https://example.com/png-icon.png",
        jpeg: "https://example.com/jpeg-icon.png",
        docx: "https://example.com/docx-icon.png",
        jpg: "https://example.com/jpg-icon.png",
        default: "https://example.com/default-icon.png"
    };

    return `<a href='${fileUrl}' download>
        <img width='30px' height='30px' src='${fileIcons[ext] || fileIcons.default}' alt='file icon'>
    </a>`;
}

// Get submitted assignments for teacher
function getSubmittedAssignments() {
    $(".loader-table").show();
    const batch_no = $('[name="batch_no"]').val() || $('[name="batch_no"]').find("option:first").val();

    $.ajax({
        url: "/dashboard/teacher/submitted-assignment",
        type: "GET",
        data: { batch_no: batch_no },
        success: function (response) {
            let assignmentsHtml = response.map((assignment) => {
                const date = new Date(assignment.created_at);
                const options = {
                    timeZone: "Asia/Karachi",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                };
                const formattedDate = date.toLocaleString("en-US", options);

                return `
                <tr>
                    <td>${assignment.topic}</td>
                    <td>${formattedDate}</td>
                    <td>${assignment.max_marks}</td>
                    <td>${displayFile(assignment.answer_file)}</td>
                </tr>`;
            }).join('');
            $("#submittedAssignmentsTableBody").html(assignmentsHtml);
        },
        error: function (xhr) {
            // Handle error appropriately
            console.error("Error fetching submitted assignments:", xhr.statusText);
        },
        complete: function () {
            $(".loader-table").hide();
        },
    });
}


// get marks for the student

$(document).ready(function () {
    $(".loader-table, .course-loading, .teacher-loading, .batch-loading, .error").hide();
    getMarks();
    getCourses();
    getCoursesTeacher();
    checkFormCompletion();

    // Image preview functionality
    $('input[name="image"]').on("change", function (e) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $("#image-preview").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    // Password show/hide functionality
    $(".toggle-password").on("click", function () {
        let passwordInput = $(this).closest(".form-control").find('input[name="password"]');
        let icon = $(this).find("i");
        let isPassword = passwordInput.attr("type") === "password";

        passwordInput.attr("type", isPassword ? "text" : "password");
        icon.toggleClass("bi-eye bi-eye-slash");
    });

    // Hide flash messages
    $(".AllowBtn").click(function () {
        $(".flash").fadeOut();
    });
});

// Get marks function
function getMarks() {
    $(".loader-table").show();
    $(".hide-table").hide();

    $.ajax({
        url: "/dashboard/student/get-marks",
        type: "GET",
        success: function (response) {
            let assignmentTableBody = "";
            let testTableBody = "";

            if (response.length === 0) {
                assignmentTableBody = createNoDataRow("No assignments marked yet");
                testTableBody = createNoDataRow("No tests marked yet");
            } else {
                response.forEach(function (mark) {
                    const createdAt = new Date(mark.answer?.created_at);
                    const rowHtml = createMarkRow(mark, createdAt);
                    if (mark?.answer?.assignment?.type == "assignment") {
                        assignmentTableBody += rowHtml;
                    } else {
                        testTableBody += rowHtml;
                    }
                });
            }

            $(".marks-table").html(assignmentTableBody);
            $(".marks-test-table").html(testTableBody);
        },
        error: function (xhr) {
            console.error(xhr.statusText);
        },
        complete: function () {
            $(".loader-table").hide();
            $(".hide-table").show();
        },
    });
}

// Create a row for marks
function createMarkRow(mark, createdAt) {
    const day = createdAt.toLocaleDateString("en-US", { weekday: "long" });
    const date = `${createdAt.getDate()}/${createdAt.getMonth() + 1}/${createdAt.getFullYear()}`;
    const time = `${createdAt.getHours()}:${createdAt.getMinutes()}`;
    return `
        <tr>
            <td class="text-sm">${date}</td>
            <td class="text-sm">${day}</td>
            <td class="text-sm">${mark.student?.name || "N/A"}</td>
            <td class="text-sm">${time}</td>
            <td class="text-sm">${displayFile(mark.answer?.answer_file)}</td>
            <td class="text-sm">${mark?.max_marks || "N/A"}</td>
            <td class="text-sm">${mark.obt_marks || "N/A"}</td>
        </tr>`;
}

// Create a no data row
function createNoDataRow(message) {
    return `<tr><td colspan="8" class="text-center text-sm">${message}</td></tr>`;
}

// Add course
$(".course-btn").click(function (e) {
    e.preventDefault();
    addCourse();
});

function addCourse() {
    $(".course-loading").show();
    $(".course-btn").attr("disabled", true).addClass("btn-disabled");

    $.ajax({
        url: "/dashboard/staff/add-course-data",
        type: "POST",
        data: $(".course-form").serialize(),
        success: function () {
            showFlashMessage("Course Added Successfully");
        },
        error: function (xhr) {
            handleCourseErrors(xhr);
        },
        complete: function () {
            $(".course-loading").hide();
            $(".course-btn").removeAttr("disabled").removeClass("btn-disabled");
            $(".course-form")[0].reset();
        },
    });
}

function handleCourseErrors(xhr) {
    if (xhr.status == 422) {
        let errors = xhr.responseJSON.errors;
        $.each(errors, function (key, value) {
            $('input[name="' + key + '"]').after(`<p class="text-danger fw-medium m-0">${value[0]}</p>`);
        });
    }
    if (xhr.status == 400) {
        showError("Course already present!");
    }
}

// Get course data
function getCourses() {
    $.ajax({
        url: "/dashboard/staff/get-course-data",
        type: "GET",
        success: function (response) {
            let courses = response.map(course => `
                <tr>
                    <td>${course.id}</td>
                    <td>${course.course_name}</td>
                    <td>${course.course_duration} months</td>
                    <td>Rs.${course.course_fee}</td>
                    <td><button class='btn btn-danger delete-course'>Delete</button></td>
                    <td><button class='btn btn-purple delete-course'>Update</button></td>
                </tr>`).join('');
            $(".courses").html(courses);
        },
        error: function (xhr) {
            console.error(xhr.statusText);
            showError("An Error Occurred");
        },
        complete: function () {
            $(".table-loader").hide();
            $(".courses-table").show();
        },
    });
}

// Get courses for teacher assignment
function getCoursesTeacher() {
    $.ajax({
        url: "/dashboard/staff/get-course-data",
        type: "GET",
        success: function (response) {
            let courses = "<option selected disabled>Select course</option>" +
                response.map(course => `<option value="${course.id}">${course.course_name}</option>`).join('');
            $("[name=course_assigned]").html(courses);
        },
        error: function (xhr) {
            console.error(xhr.statusText);
            showError("An Error Occurred");
        },
    });
}

// Add instructor
$(".teacher-btn").click(function (e) {
    e.preventDefault();
    addInstructor();
});

function addInstructor() {
    $(".teacher-loading").show();
    $(".teacher-btn").attr("disabled", true).addClass("btn-disabled");
    $(".text-danger").remove(); // Clear previous errors

    let formData = new FormData($(".teacher-form")[0]);

    $.ajax({
        url: "/dashboard/staff/add-instructor",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === "success") {
                showFlashMessage("Instructor added successfully!");
                $(".teacher-form")[0].reset();
                checkFormCompletion();
            } else {
                showError("Error adding instructor.");
            }
        },
        error: function (xhr) {
            handleInstructorErrors(xhr);
        },
        complete: function () {
            $(".teacher-loading").hide();
            $(".teacher-btn").attr("disabled", true).addClass("btn-disabled");
        },
    });
}

function handleInstructorErrors(xhr) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        $.each(errors, function (key, value) {
            $('input[name="' + key + '"]').after(`<p class="text-danger fw-medium m-0">${value[0]}</p>`);
        });
    } else {
        showError("An Error Occurred");
    }
}

// Check form completion
$(".teacher-form input, .teacher-form select").on("input change", function () {
    checkFormCompletion();
});

function checkFormCompletion() {
    let allFilled = $(".teacher-form input, .teacher-form select").toArray().every(input => $(input).val() !== "");
    $(".teacher-btn").attr("disabled", !allFilled).toggleClass("btn-disabled", !allFilled);
}

// Show flash message
function showFlashMessage(message) {
    $(".flash").show();
    $(".notificationPara").html(message);
    $(".AllowBtn").off("click").on("click", function () {
        $(".flash").fadeOut();
    });
}

// Show error message
function showError(message) {
    $(".error").show();
    $(".error__title").html(message);
    $(".error__close").off("click").on("click", function () {
        $(".error").fadeOut();
    });
}

// Assign batches
$(document).ready(function () {
    $(".batch-btn").attr("disabled", true).addClass("btn-disabled");

    // Initially hide course and teacher fields
    $("select[name='course_name_batch'], select[name='teacher_assigned']").hide();

    // Fetch courses on page load
    $.ajax({
        url: "/dashboard/staff/get-courses",
        type: "GET",
        beforeSend: function () {
            $(".teacher-skeleton-course").show(); // Show a loading skeleton or spinner
        },
        success: function (response) {
            let courseOptions = "<option disabled selected>Select Course</option>" +
                response.map(course => `<option value="${course.id}">${course.course_name}</option>`).join('');
            $('select[name="course_name_batch"]').html(courseOptions).show(); // Show the select after populating
        },
        error: function (xhr) {
            console.error(xhr.statusText);
        },
        complete: function () {
            $(".teacher-skeleton-course").hide(); // hide the loading skeleton or spinner
        },
    });

    // Fetch teachers based on selected course
    $('select[name="course_name_batch"]').change(function () {
        const course_id = $(this).val();
        $(".teacher-skeleton").show(); // Show loading indicator for teachers
        $("select[name='teacher_assigned']").hide(); // Hide the teacher select while loading

        $.ajax({
            url: "/dashboard/staff/get-teachers",
            type: "POST",
            data: { course_id, _token: $('input[name="_token"]').val() },
            success: function (response) {
                let teacherOptions = "<option disabled selected>Select Teacher</option>" +
                    response.map(teacher => `<option value="${teacher.id}">${teacher.name}</option>`).join('');
                $('select[name="teacher_assigned"]').html(teacherOptions).show(); // Show the select after populating
            },
            error: function (xhr) {
                console.error(xhr.statusText);
            },
            complete: function () {
                $(".teacher-skeleton").hide(); // Hide loading indicator for teachers
                checkFormValidity();
            },
        });
    });

    // Check if both course and teacher are selected
    $('select[name="teacher_assigned"]').change(checkFormValidity);

    // Add batch form submission
    $(".batch-btn").click(function (e) {
        e.preventDefault();
        addBatch();
    });

    // Function to check form validity
    function checkFormValidity() {
        const courseSelected = $('select[name="course_name_batch"]').val();
        const teacherSelected = $('select[name="teacher_assigned"]').val();
        $(".batch-btn").attr("disabled", !(courseSelected && teacherSelected)); // Enable/disable the button based on selection
        $(".batch-btn").removeClass("btn-disabled", !(courseSelected && teacherSelected)); // Enable/disable the button based on selection

    }
});

// Check form validity for batch
function checkFormValidity() {
    const courseSelected = $('select[name="course_name_batch"]').val();
    const teacherSelected = $('select[name="teacher_assigned"]').val();
    const batchSelected = $('input[name="batch_number"]').val();
    $(".batch-btn").attr("disabled", !(courseSelected && teacherSelected && batchSelected)).toggleClass("btn-disabled", !(courseSelected && teacherSelected && batchSelected));
}

// Add batch function
function addBatch() {
    $(".batch-loading").show();
    $(".batch-btn").attr("disabled", true).addClass("btn-disabled");

    let formData = new FormData($(".course-form")[0]);

    $.ajax({
        url: "/dashboard/staff/add-batch",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === "success") {
                $(".course-form")[0].reset();
                showFlashMessage("Batch added successfully");
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    $(`input[name="${key}"], select[name="${key}"]`).after(`<p class="text-danger">${value[0]}</p>`);
                });
            }
        },
        complete: function () {
            $(".batch-loading").hide();
            checkFormValidity();
        },
    });
}


$(document).ready(function () {
    loadBatches(1); // Load first page of batches on page load

    // Handle pagination click
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).attr("href").split("page=")[1];
        loadBatches(page);
    });

    // Update batch modal
    $(document).on("click", ".update-btn", function () {
        const batchId = $(this).data("id");
        fetchBatchDetails(batchId);
    });

    // Fetch teachers when course changes
    $(document).on("change", "#courseAssigned", function () {
        const courseId = $(this).val();
        fetchTeachersAndDuration(courseId);
    });

    // Save batch changes
    $("#saveBatchBtn").click(function () {
        const batchId = $("#batchId").val();
        const formData = getBatchFormData();
        saveBatch(batchId, formData);
    });

    // Delete batch
    $(document).on("click", ".delete-btn", function (e) {
        e.preventDefault();
        const batchId = $(this).data("id");
        confirmAndDeleteBatch(batchId, $(this));
    });
});

// Load batches
function loadBatches(page) {
    $.ajax({
        url: `/dashboard/staff/batches/view-batches/?page=${page}`,
        type: "GET",
        success: function (response) {
            $(".batches-view").html(response.batchesHtml);
            $(".pagination").html(response.paginationHtml);
        },
        error: function (xhr) {
            console.error(xhr.statusText);
        },
    });
}

// Fetch batch details for updating
function fetchBatchDetails(batchId) {
    $.ajax({
        url: `/dashboard/staff/batches/${batchId}/edit`,
        type: "GET",
        beforeSend: function () {
            toggleSaveButton(true);
        },
        success: function (response) {
            $("#batchNo").val(response.batch_no);
            $("#courseAssigned").html(response.courseOptions);
            $("#teacherAssigned").html(response.teacherOptions);
            $("#duration").val(response.duration);
            $("#batchId").val(batchId);
            toggleSaveButton(false);
        },
        error: function (xhr) {
            console.error("Error fetching batch details:", xhr.statusText);
        },
    });
}

// Fetch teachers and duration based on selected course
function fetchTeachersAndDuration(courseId) {
    $.ajax({
        url: `/dashboard/staff/get-teachers-and-duration`,
        type: "POST",
        data: {
            course_id: courseId,
            _token: $('input[name="_token"]').val(),
        },
        success: function (response) {
            let teacherOptions = "<option disabled selected>Select Teacher</option>";
            response.teachers.forEach(function (teacher) {
                teacherOptions += `<option value="${teacher.id}">${teacher.name}</option>`;
            });
            $("#teacherAssigned").html(teacherOptions);
            $("#duration").val(response.course_duration);
        },
        error: function (xhr) {
            console.error("Error fetching teachers or duration:", xhr.statusText);
        },
    });
}

// Get batch form data
function getBatchFormData() {
    return {
        batch_no: $("#batchNo").val(),
        teacher: $("#teacherAssigned").val(),
        course_id: $("#courseAssigned").val(),
        duration: $("#duration").val(),
        _token: $('input[name="_token"]').val(),
    };
}

// Save changes to the batch
function saveBatch(batchId, formData) {
    toggleSaveButton(true, 'Saving...');
    $.ajax({
        url: `/dashboard/staff/update-batch/${batchId}`,
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.status === "success") {
                alert(response.message);
                $("#updateBatchModal").modal("hide");
                loadBatches(1);
            }
        },
        error: function (xhr) {
            console.error("Error updating batch:", xhr.statusText);
        },
        complete: function () {
            toggleSaveButton(false);
        },
    });
}

// Confirm and delete a batch
function confirmAndDeleteBatch(batchId, button) {
    if (confirm("Are you sure you want to delete this batch?")) {
        button.attr("disabled", "disabled").html('<span class="spinner-border spinner-border-sm"></span> Deleting...');
        $.ajax({
            url: `/dashboard/staff/delete-batch/${batchId}`,
            type: "DELETE",
            data: {
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    $(`#batch-row-${batchId}`).remove();
                    loadBatches(1);
                } else {
                    alert("Something went wrong, please try again.");
                }
            },
            error: function (xhr) {
                console.error("Error deleting batch:", xhr.statusText);
            },
            complete: function () {
                button.html('Delete').removeAttr("disabled");
            },
        });
    }
}

// Toggle save button state
function toggleSaveButton(disable, loadingText = "Save Changes") {
    const saveButton = $("#saveBatchBtn");
    if (disable) {
        saveButton.attr("disabled", "disabled").addClass("btn-disabled").html('<span class="spinner-border spinner-border-sm"></span> ' + loadingText);
    } else {
        saveButton.removeAttr("disabled").removeClass("btn-disabled").html(loadingText);
    }
}
