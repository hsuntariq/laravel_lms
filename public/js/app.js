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

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Function to count assignments
    function countAssignments() {
        const batch_no =
            $('[name="batch_no"]').val() ||
            $('[name="batch_no"]').find("option:first").val();
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
                console.error(
                    "Error fetching assignment count:",
                    xhr.statusText
                );
            },

            complete: function () {
                $(".count-loading").hide();
                $(".total-assignments, .total-tests").show();
            },
        });
    }

    // Count assignments on page load
    $(document).ready(function () {
        if (
            window.location.pathname === "/dashboard/teacher/assignments/upload"
        ) {
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
                let assignmentsHtml = response
                    .map((assignment, index) => {
                        const createdAtDate = new Date(assignment.created_at);
                        const options = {
                            timeZone: "Asia/Karachi",
                            month: "2-digit",
                            day: "2-digit",
                            hour: "2-digit",
                            minute: "2-digit",
                        };
                        const formattedCreatedAt = createdAtDate.toLocaleString(
                            "en-US",
                            options
                        );

                        return `
                <tr>
                    <td class="text-sm">${index + 1}</td>
                    <td class="text-sm">${assignment.topic}</td>
                    <td class="text-sm">${assignment.max_marks}</td>
                    <td class="text-sm">${formattedCreatedAt}</td>
                    <td class="text-sm">${formattedCreatedAt}</td>
                    ${
                        assignment?.answers?.length > 0
                            ? `<td colspan="4" class="text-center">
                            <i class="bi bi-check-circle-fill text-success"></i> Submitted
                        </td>`
                            : `<td class="text-sm">pending...</td>
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
                    })
                    .join("");

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
                    displayValidationErrors(
                        xhr.responseJSON.errors,
                        errorMessageDiv
                    );
                } else {
                    // Handle error appropriately
                    showFlashMessage(
                        "Failed to upload the assignment",
                        "error"
                    );
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
        $(".flash").addClass(
            type === "success" ? "alert-success" : "alert-danger"
        );
        $(".AllowBtn")
            .off("click")
            .on("click", function () {
                $(".flash").fadeOut();
            });
    }

    // Function to display validation errors
    function displayValidationErrors(errors, errorMessageDiv) {
        $.each(errors, function (key, value) {
            const errorElement = `<p class="text-danger fw-medium m-0">${value[0]}</p>`;
            $(`input[name="${key}"], select[name="${key}"]`).after(
                errorElement
            );
        });
        errorMessageDiv.show();
    }

    // Get the related day
    function getDay(day) {
        const days = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ];
        return days[day] || "";
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
            default: "https://example.com/default-icon.png",
        };

        return `<a href='${fileUrl}' download>
        <img width='30px' height='30px' src='${
            fileIcons[ext] || fileIcons.default
        }' alt='file icon'>
    </a>`;
    }

    // Get submitted assignments for teacher
    function getSubmittedAssignments() {
        $(".loader-table").show();
        const batch_no =
            $('[name="batch_no"]').val() ||
            $('[name="batch_no"]').find("option:first").val();

        $.ajax({
            url: "/dashboard/teacher/submitted-assignment",
            type: "GET",
            data: { batch_no: batch_no },
            success: function (response) {
                let assignmentsHtml = response
                    .map((assignment) => {
                        const date = new Date(assignment.created_at);
                        const options = {
                            timeZone: "Asia/Karachi",
                            month: "2-digit",
                            day: "2-digit",
                            hour: "2-digit",
                            minute: "2-digit",
                        };
                        const formattedDate = date.toLocaleString(
                            "en-US",
                            options
                        );

                        return `
                <tr>
                    <td>${assignment.topic}</td>
                    <td>${formattedDate}</td>
                    <td>${assignment.max_marks}</td>
                    <td>${displayFile(assignment.answer_file)}</td>
                </tr>`;
                    })
                    .join("");
                $("#submittedAssignmentsTableBody").html(assignmentsHtml);
            },
            error: function (xhr) {
                // Handle error appropriately
                console.error(
                    "Error fetching submitted assignments:",
                    xhr.statusText
                );
            },
            complete: function () {
                $(".loader-table").hide();
            },
        });
    }

    // get marks for the student

    $(document).ready(function () {
        $(
            ".loader-table, .course-loading, .teacher-loading, .batch-loading, .error"
        ).hide();
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
            let passwordInput = $(this)
                .closest(".form-control")
                .find('input[name="password"]');
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
                    assignmentTableBody = createNoDataRow(
                        "No assignments marked yet"
                    );
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
        const date = `${createdAt.getDate()}/${
            createdAt.getMonth() + 1
        }/${createdAt.getFullYear()}`;
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
                $(".course-btn")
                    .removeAttr("disabled")
                    .removeClass("btn-disabled");
                $(".course-form")[0].reset();
            },
        });
    }

    function handleCourseErrors(xhr) {
        if (xhr.status == 422) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                $('input[name="' + key + '"]').after(
                    `<p class="text-danger fw-medium m-0">${value[0]}</p>`
                );
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
                let courses = response
                    .map(
                        (course) => `
                <tr>
                    <td>${course.id}</td>
                    <td>${course.course_name}</td>
                    <td>${course.course_duration} months</td>
                    <td>Rs.${course.course_fee}</td>
                    <td><button class='btn btn-danger delete-course'>Delete</button></td>
                    <td><button class='btn btn-purple delete-course'>Update</button></td>
                </tr>`
                    )
                    .join("");
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
                let courses =
                    "<option selected disabled>Select course</option>" +
                    response
                        .map(
                            (course) =>
                                `<option value="${course.id}">${course.course_name}</option>`
                        )
                        .join("");
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
                $(".teacher-btn")
                    .attr("disabled", true)
                    .addClass("btn-disabled");
                $("#image-preview").hide();
            },
        });
    }

    function handleInstructorErrors(xhr) {
        if (xhr.status === 422) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                $('input[name="' + key + '"]').after(
                    `<p class="text-danger fw-medium m-0">${value[0]}</p>`
                );
            });
        } else {
            showError("An Error Occurred");
        }
    }

    // Check form completion
    $(".teacher-form input, .teacher-form select").on(
        "input change",
        function () {
            checkFormCompletion();
        }
    );

    function checkFormCompletion() {
        let allFilled = $(".teacher-form input, .teacher-form select")
            .toArray()
            .every((input) => $(input).val() !== "");
        $(".teacher-btn")
            .attr("disabled", !allFilled)
            .toggleClass("btn-disabled", !allFilled);
    }

    // Show flash message
    function showFlashMessage(message) {
        $(".flash").show();
        $(".notificationPara").html(message);
        $(".AllowBtn")
            .off("click")
            .on("click", function () {
                $(".flash").fadeOut();
            });
    }

    // Show error message
    function showError(message) {
        $(".error").show();
        $(".error__title").html(message);
        $(".error__close")
            .off("click")
            .on("click", function () {
                $(".error").fadeOut();
            });
    }

    // Assign batches
    $(document).ready(function () {
        $(".batch-btn").attr("disabled", true).addClass("btn-disabled");

        // Initially hide course and teacher fields
        $(
            "select[name='course_name_batch'], select[name='teacher_assigned']"
        ).hide();

        // Fetch courses on page load
        $.ajax({
            url: "/dashboard/staff/get-courses",
            type: "GET",
            beforeSend: function () {
                $(".teacher-skeleton-course").show(); // Show a loading skeleton or spinner
            },
            success: function (response) {
                let courseOptions =
                    "<option disabled selected>Select Course</option>" +
                    response
                        .map(
                            (course) =>
                                `<option value="${course.id}">${course.course_name}</option>`
                        )
                        .join("");
                $('select[name="course_name_batch"]')
                    .html(courseOptions)
                    .show(); // Show the select after populating
            },
            error: function (xhr) {
                console.error(xhr.statusText);
            },
            complete: function () {
                $(".teacher-skeleton-course").hide(); // Hide the loading skeleton or spinner
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
                    let teacherOptions =
                        "<option disabled selected>Select Teacher</option>" +
                        response
                            .map(
                                (teacher) =>
                                    `<option value="${teacher.id}">${teacher.name}</option>`
                            )
                            .join("");
                    $('select[name="teacher_assigned"]')
                        .html(teacherOptions)
                        .show(); // Show the select after populating
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
        $(".batch-btn")
            .off("click")
            .on("click", function (e) {
                e.preventDefault();
                addBatch();
            });

        // Function to check form validity
    });
    function checkFormValidity() {
        const courseSelected = $('select[name="course_name_batch"]').val();
        const teacherSelected = $('select[name="teacher_assigned"]').val();
        const batchSelected = $('input[name="batch_number"]').val();
        $(".batch-btn")
            .attr(
                "disabled",
                !(courseSelected && teacherSelected && batchSelected)
            )
            .toggleClass(
                "btn-disabled",
                !(courseSelected && teacherSelected && batchSelected)
            );
    }

    // Add batch function with error handling
    function addBatch() {
        $(".batch-loading").show();
        $(".batch-btn").attr("disabled", true).addClass("btn-disabled");

        let formData = new FormData($(".course-form")[0]);

        // Clear previous error messages
        $(".text-danger").remove();

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
                    $('input[name = "batch_number"]').removeClass("is-invalid");
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Find the input or select element and append the error message
                    $(`input[name="batch_number"]`)
                        .after(
                            `<p class="text-danger">${errors?.batch_number}</p>`
                        )
                        .addClass("is-invalid"); // Add 'is-invalid' class for better UI feedback

                    showFlashMessage(
                        "Please fix the errors and try again",
                        "error"
                    );
                }
            },
            complete: function () {
                $(".batch-loading").hide();
                checkFormValidity(); // Check if the form can be submitted again
            },
        });
    }

    // Function to show flash messages for success or error
    function showFlashMessage(message, type = "success") {
        $(".flash").show();
        $(".notificationPara").html(message).fadeIn();

        setTimeout(function () {
            $(".flash").fadeOut();
        }, 3000); // Flash message disappears after 3 seconds
    }

    $(document).ready(function () {
        loadBatches(1); // Load first page of batches on page load

        // Handle pagination click
        $(document).on(
            "click",
            ".batch-pagination .pagination a",
            function (e) {
                e.preventDefault();
                const page = $(this).attr("href").split("page=")[1];
                loadBatches(page);
            }
        );

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
                let teacherOptions =
                    "<option disabled selected>Select Teacher</option>";
                response.teachers.forEach(function (teacher) {
                    teacherOptions += `<option value="${teacher.id}">${teacher.name}</option>`;
                });
                $("#teacherAssigned").html(teacherOptions);
                $("#duration").val(response.course_duration);
            },
            error: function (xhr) {
                console.error(
                    "Error fetching teachers or duration:",
                    xhr.statusText
                );
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
        toggleSaveButton(true, "Saving...");
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
            button
                .attr("disabled", "disabled")
                .html(
                    '<span class="spinner-border spinner-border-sm"></span> Deleting...'
                );
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
                    button.html("Delete").removeAttr("disabled");
                },
            });
        }
    }

    // Toggle save button state
    function toggleSaveButton(disable, loadingText = "Save Changes") {
        const saveButton = $("#saveBatchBtn");
        if (disable) {
            saveButton
                .attr("disabled", "disabled")
                .addClass("btn-disabled")
                .html(
                    '<span class="spinner-border spinner-border-sm"></span> ' +
                        loadingText
                );
        } else {
            saveButton
                .removeAttr("disabled")
                .removeClass("btn-disabled")
                .html(loadingText);
        }
    }

    $(document).ready(function () {
        // Toggle password visibility
        $(".toggle-password").click(function () {
            const input = $(this).siblings(".pass");
            const icon = $(this).find("i");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("bi-eye-slash").addClass("bi-eye");
            } else {
                input.attr("type", "password");
                icon.removeClass("bi-eye").addClass("bi-eye-slash");
            }
        });

        // Image preview
        $("#image").change(function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $("#image-preview").attr("src", e.target.result).show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Fetch batches based on selected course
        $("#course_assigned").change(function () {
            const courseId = $(this).val();
            $("#batch_assigned").html("<option>Loading batches...</option>");

            $.ajax({
                url: "/dashboard/staff/get-batches",
                type: "POST",
                data: {
                    course_id: courseId,
                    _token: $('input[name="_token"]').val(),
                },
                success: function (response) {
                    let batchOptions =
                        "<option disabled selected>Loading batches...</option>";
                    response.batches.forEach(function (batch) {
                        batchOptions += `<option value="${batch.id}">${batch.batch_no}</option>`;
                    });
                    $("#batch_assigned").html(batchOptions);
                },
                error: function (xhr) {
                    console.error(xhr.statusText);
                },
            });
        });
    });

    // add student
    $(document).ready(function () {
        // Initially disable the submit button
        $(".student-btn").attr("disabled", true).addClass("btn-disabled");
        $(".student-loading").hide();
        // Monitor the form fields for changes to enable the submit button
        $(".student-form input, .student-form select").on(
            "input change",
            function () {
                checkFormValidity();
            }
        );

        // Add student form submission
        $(".student-btn")
            .off("click")
            .on("click", function (e) {
                e.preventDefault();
                addStudent();
            });

        // Function to add student
        function addStudent() {
            $(".student-loading").text("Adding...").show(); // Show loading text
            $(".student-btn").attr("disabled", true).addClass("btn-disabled");

            let formData = new FormData($(".student-form")[0]);

            $.ajax({
                url: "/dashboard/staff/add-student",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === "success") {
                        // Reset form after successful submission
                        $(".student-form")[0].reset();
                        removeErrors(); // Remove error messages
                        showFlashMessage(
                            "Student added successfully",
                            "success"
                        );
                        $("#image-preview").hide();

                        // Disable the button again after form reset
                        checkFormValidity(); // Check validity after reset to re-disable the button
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $(
                                `input[name="${key}"], select[name="${key}"]`
                            ).after(`<p class="text-danger">${value[0]}</p>`);
                        });
                    }
                },
                complete: function () {
                    $(".student-loading").hide(); // Hide loading text
                    $(".student-btn")
                        .attr("disabled", true)
                        .addClass("btn-disabled");
                },
            });
        }

        // Function to show flash messages
        function showFlashMessage(message, type) {
            if (type === "success") {
                $(".flash").show();
                $(".notificationPara").html(message);
                setTimeout(function () {
                    $(".flash").fadeOut("slow", function () {
                        $(this).remove();
                    });
                }, 2000); // Adjust time as needed
            }
        }
    });

    // Function to check form validity
    function checkFormValidity() {
        const allFieldsFilled = $(".student-form input, .student-form select")
            .toArray()
            .every(function (field) {
                return $(field).val().trim() !== "";
            });

        if (allFieldsFilled) {
            $(".student-btn")
                .attr("disabled", false)
                .removeClass("btn-disabled");
        } else {
            $(".student-btn").attr("disabled", true).addClass("btn-disabled");
        }
    }

    // Function to remove error messages
    function removeErrors() {
        $(".text-danger").remove(); // Remove all error messages
    }

    // Fetch courses and teachers with loading text in bold
    function fetchCourses() {
        $.ajax({
            url: "/dashboard/staff/get-courses",
            type: "GET",
            beforeSend: function () {
                $("select[name='course_name']").html(
                    "<option><b>Loading...</b></option>"
                );
            },
            success: function (response) {
                let courseOptions =
                    "<option disabled selected>Select Course</option>" +
                    response
                        .map(
                            (course) =>
                                `<option value="${course.id}">${course.course_name}</option>`
                        )
                        .join("");
                $("select[name='course_name']").html(courseOptions);
            },
            error: function (xhr) {
                console.error(xhr.statusText);
            },
        });
    }

    // Fetch teachers after selecting a course
    $("select[name='course_name']").change(function () {
        const courseId = $(this).val();
        fetchTeachers(courseId);
    });

    function fetchTeachers(courseId) {
        $.ajax({
            url: "/dashboard/staff/get-teachers",
            type: "POST",
            data: {
                course_id: courseId,
                _token: $('input[name="_token"]').val(),
            },
            beforeSend: function () {
                $("select[name='teacher_assigned']").html(
                    "<option><b>Loading...</b></option>"
                );
            },
            success: function (response) {
                let teacherOptions =
                    "<option disabled selected>Select Teacher</option>" +
                    response
                        .map(
                            (teacher) =>
                                `<option value="${teacher.id}">${teacher.name}</option>`
                        )
                        .join("");
                $("select[name='teacher_assigned']").html(teacherOptions);
            },
            error: function (xhr) {
                console.error(xhr.statusText);
            },
        });
    }

    // Initial course and teacher load
    fetchCourses();
});

function loadStudents(page = 1) {
    let courseId = $(".courses-select").val() || "";
    let batchId = $(".batch-select").val() || "";

    // Show loading spinner and hide the student table
    $(".staff-loader").show();
    $(".student-table").hide();

    $.ajax({
        url: `/dashboard/staff/get-students`,
        type: "GET",
        data: {
            page: page,
            course_id: courseId, // Send selected course to server
            batch_id: batchId, // Send selected batch to server
        },
        success: function (response) {
            // Check if there are any students to display
            console.log(response);
            if (response.studentsHtml) {
                $(".students").html(response.studentsHtml); // Update student table with the fetched data
            } else {
                // Show 'No Students' message if no data found
                $(".students").html(
                    `<tr>
                            <th class='text-center' colspan="7"> No Students in this batch</th>
                        </tr>`
                );
            }
            $(".pagination").html(response.students_pagination); // Update pagination with the response
        },
        complete: function () {
            // Hide the loader and show the student table after the request is complete
            $(".staff-loader").hide();
            $(".student-table").show();
        },
        error: function (xhr) {
            console.error(xhr.statusText);
            alert("Error loading students. Please try again.");
            // Handle error if needed (e.g., show error message or retry option)
        },
    });
}

// Fetch and display students
$(document).ready(function () {
    // Initial load of students on page load
    loadStudents(1);

    // Function to load students based on filters (course, batch, and pagination)
    // Load batches when course is selected
    $(document).on("change", ".courses-select", function () {
        let courseId = $(this).val();
        // if (!courseId) return; // Prevent AJAX call if no course is selected

        // Show loader while fetching batches and students
        $(".staff-loader").show();
        $(".student-table").hide();

        // Fetch batches for the selected course
        $.ajax({
            url: `/dashboard/staff/get-batches/${courseId}`,
            type: "GET",
            data: {
                course_id: courseId,
            },
            beforeSend: function () {
                $(".batch-select").html(
                    "<option disabled selected>Loading batches...</option>"
                );
            },
            success: function (response) {
                let batchOptions =
                    "<option disabled selected>Select Batch</option>";
                // Populate batch dropdown based on the selected course
                response.options.data.forEach(function (batch) {
                    batchOptions += `<option value="${batch.id}">${batch.batch_no}</option>`;
                });
                $(".batch-select").html(batchOptions); // Update the batch select options
                console.log(response);
                $(".total-students").html(
                    `Total Students(${response.options.data.length})`
                );
            },
            complete: function () {
                // Hide the loader and show the student table after fetching batches
                $(".staff-loader").hide();
                $(".student-table").show();
            },
            error: function (xhr) {
                console.error("Error loading batches", xhr.statusText);
                alert("Error loading batches. Please try again.");
            },
        });

        // Reset batch selection and load students after changing the course
        $(".batch-select").html(
            "<option disabled selected>Loading batches...</option>"
        );
        loadStudents(1); // Reload students after selecting a new course
    });

    // Load students when a batch is selected
    $(document).on("change", ".batch-select", function () {
        loadStudents(1); // Reload students after changing the batch
    });

    // Event handler for pagination click
    $(document).on("click", ".student-pagination .pagination a", function (e) {
        e.preventDefault();
        let page = $(this).attr("href").split("page=")[1];
        loadStudents(page); // Load students for the selected page in pagination
    });
});

// Event handler for deleting a student
$(document).on("click", ".delete-student", function (e) {
    e.preventDefault();
    let studentId = $(this).data("id");
    $(this).attr("disabled", true).text("Deleting...");

    if (confirm("Are you sure you want to delete this student?")) {
        $.ajax({
            url: "/dashboard/staff/delete-student/" + studentId,
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
            },
            beforeSend: function () {
                // Show a loading message or spinner if necessary
                $(`.delete-student[data-id="${studentId}"]`)
                    .attr("disabled", true)
                    .text("Deleting...");
            },
            success: function (response) {
                $(".flash").show();
                $(".notificationPara").html("Deleted Successfully!");
                loadStudents(1); // Reload students after delete
            },

            error: function (xhr) {
                showFlashMessage("Error deleting student.", "danger");
            },
        });
    }
});

// Flash message function
function showFlashMessage(message, type) {
    $(".flash")
        .html(`<p class="${type}">${message}</p>`)
        .fadeIn()
        .delay(2000)
        .fadeOut();
}

function loadStudentForEdit(studentId) {
    $.ajax({
        url: `/dashboard/staff/get-student/${studentId}`,
        type: "GET",
        beforeSend: function () {
            // Optionally show loading inside the modal
            $("#editStudentModal").modal("show");
            // $(".modal-body").html(`<p>Loading student details...</p>`);
        },
        success: function (student) {
            console.log(student);
            // Populate the form fields with the student's data
            $('input[name="student_id"]').val(student.id);
            $('input[name="student_name"]').val(student.name);
            $('input[name="student_email"]').val(student.email);
            $('select[name="batch"]').val(student.student_batch.id); // Assuming batch ID is used
        },
        error: function (xhr) {
            $(".modal-body").html(`<p>Error loading student details.</p>`);
        },
    });
}

// Function to handle form submission for updating student details
$(document).on("submit", ".student-edit-form", function (e) {
    e.preventDefault();

    const studentId = $('input[name="student_id"]').val();
    let formData = new FormData($(this)[0]);

    $.ajax({
        url: `/dashboard/staff/update-student/${studentId}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function () {
            $(".update-student-btn").attr("disabled", true).text("Updating...");
        },
        success: function (response) {
            if (response.status === "success") {
                showFlashMessage("Student updated successfully", "success");
                $("#editStudentModal").modal("hide");
                loadStudents(); // Refresh the student list
            }
        },
        error: function (xhr) {
            let errors = xhr.responseJSON.errors;
            $.each(errors, function (key, value) {
                $(`input[name="${key}"], select[name="${key}"]`).after(
                    `<p class="text-danger">${value[0]}</p>`
                );
            });
        },
        complete: function () {
            $(".update-student-btn")
                .attr("disabled", false)
                .text("Update Student");
        },
    });
});

function deleteStudent(studentId) {
    $.ajax({
        url: `/dashboard/staff/delete-student/${studentId}`,
        type: "DELETE", // DELETE request type
        beforeSend: function () {
            // Show a loading message or spinner if necessary
            $(`.delete-student[data-id="${studentId}"]`)
                .attr("disabled", true)
                .text("Deleting...");
        },
        success: function (response) {
            if (response.status === "success") {
                // Remove the deleted student row from the table
                $(`tr[data-id="${studentId}"]`).remove();
                $(`tr[data-id="${studentId}"]`).fadeOut();

                showFlashMessage(response.message, "success");
            }
        },
        error: function (xhr) {
            showFlashMessage(
                "Error deleting student. Please try again.",
                "danger"
            );
        },
        complete: function () {
            // Reset the button state
            $(`.delete-student[data-id="${studentId}"]`)
                .attr("disabled", false)
                .text("Delete");
        },
    });
}

function showFlashMessage(message, type) {
    $(".flash").show();
    $(".notificationPara").html(message);

    setTimeout(function () {
        $(".flash").fadeOut();
    }, 3000);
}

$(document).ready(function () {
    fetchCourses();

    // Load student details into the edit modal
    $(document).on("click", ".edit-student", function (e) {
        e.preventDefault();
        let studentId = $(this).data("id");

        $.ajax({
            url: `/dashboard/staff/edit-student/${studentId}`,
            type: "GET",
            beforeSend: function () {
                // Show the modal and reset the form
                $("#editStudentModal").modal("show");
                $("#edit_course").html("<option>Loading courses...</option>");
                $("#edit_batch").html("<option>Loading batches...</option>");
            },
            success: function (response) {
                // Populate the form fields with the student's current data
                $("#edit_student_id").val(response.id);
                $("#edit_student_name").val(response.name);
                $("#edit_student_email").val(response.email);
                $("#edit_student_whatsapp").val(response.whatsapp);
                $("#edit_student_gender").val(response.gender);

                // Load courses into the dropdown
                fetchCourses(response.course_assigned, response.batch_assigned);
            },
            error: function () {
                alert("Error loading student details");
            },
        });
    });

    // Fetch courses and populate the dropdown
    function fetchCourses(selectedCourseId = null, selectedBatchId = null) {
        $.ajax({
            url: "/dashboard/staff/get-courses", // Adjust the URL as necessary
            type: "GET",
            beforeSend: function () {
                $(".courses-select").html(
                    `<option>Loading courses...</option>`
                );
            },
            success: function (response) {
                let coursesHtml =
                    "<option disabled selected>Select Course</option>";
                response.forEach((course) => {
                    coursesHtml += `<option value="${course.id}" ${
                        selectedCourseId === course.id ? "selected" : ""
                    }>${course.course_name}</option>`;
                });
                $("#edit_course").html(coursesHtml);
                $(".courses-select").html(coursesHtml);
                // Fetch batches based on the selected course
                $("#edit_course").on("change", function () {
                    fetchBatches(selectedCourseId, selectedBatchId);
                });
            },
            error: function () {
                $("#edit_course").html(
                    "<option>Error loading courses</option>"
                );
            },
        });
    }

    // Fetch batches based on the selected course
    function fetchBatches(courseId, selectedBatchId = null) {
        $.ajax({
            url: "/dashboard/staff/get-batches", // Adjust the URL as necessary
            type: "POST",
            data: {
                course_id: courseId,
                _token: $('input[name="_token"]').val(), // Include CSRF token
            },
            success: function (response) {
                console.log(response);
                let batchesHtml =
                    "<option disabled selected>Loading batches...</option>";
                response.batches.forEach((batch) => {
                    batchesHtml += `<option value="${batch.id}" ${
                        selectedBatchId === batch.id ? "selected" : ""
                    }>${batch.batch_no}</option>`;
                });
                $("#edit_batch").html(batchesHtml);
            },
            error: function () {
                $("#edit_batch").html("<option>Error loading batches</option>");
            },
        });
    }

    // Enable/disable update button based on form validity
    $("input, select").on("change keyup", function () {
        checkFormValidity();
    });

    function checkFormValidity() {
        const name = $("#edit_student_name").val();
        const email = $("#edit_student_email").val();
        const whatsapp = $("#edit_student_whatsapp").val();
        const gender = $("#edit_student_gender").val();
        const course = $("#edit_course").val();
        const batch = $("#edit_batch").val();

        if (name && email && whatsapp && gender && course && batch) {
            $(".update-student-btn").attr("disabled", false);
        } else {
            $(".update-student-btn").attr("disabled", true);
        }
    }

    // Handle update student form submission
    $("#editStudentForm").on("submit", function (e) {
        e.preventDefault();
        let studentId = $("#edit_student_id").val();
        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: `/dashboard/staff/update-student/${studentId}`,
            type: "PUT",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    // Close the modal
                    $("#editStudentModal").modal("hide");

                    // Reload student list (or update the row dynamically)
                    loadStudents();
                    alert(response.message); // Show success message
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $(`input[name="${key}"], select[name="${key}"]`).after(
                            `<p class="text-danger">${value[0]}</p>`
                        );
                    });
                }
            },
        });
    });
});

// Sign Up User
function signUpUser(username, email, password) {
    $.ajax({
        url: "/sign-up",
        type: "POST",
        dataType: "json",
        data: {
            username: username,
            email: email,
            password: password,
            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
        },
        success: function (response) {
            alert(response.message);
            // Handle success (e.g., redirect to home or dashboard)
        },
        error: function (xhr) {
            alert(xhr.responseJSON.message || "Error occurred during sign up.");
        },
    });
}

// Sign In User
function signInUser(email, password) {
    $(".sign-in-btn").attr("disabled", true);
    $(".sign-in-btn").addClass("btn-disabled");
    $(".sign-in-btn").html(`<img class="batch-loading"
                src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                width="20px" alt="Signing In loading"> Signing in...`);

    $.ajax({
        url: "/sign-in",
        type: "POST",
        dataType: "json",
        beforeSend: function () {
            $(".text-danger").remove();
            $(".error-message").removeClass("is-invalid");
        },
        data: {
            email: email,
            password: password,
            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
        },
        success: function (response) {
            // alert(response.message);
            // Handle success (e.g., redirect to dashboard)
            if (response.role == "student") {
                window.location.assign(
                    `/dashboard/student/home/${response?.user?.id}`
                );
            } else if (response.role == "teacher") {
                window.location.assign(
                    `/dashboard/teacher/home/${response?.user?.id}`
                );
            } else if (response.role == "staff") {
                window.location.assign(
                    `/dashboard/staff/home/${response?.user?.id}`
                );
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let index = 0;
                $.each(errors, function (key, messages) {
                    messages.forEach(function (message) {
                        $(`input[name="${key}"]`)
                            .closest(".d-flex")
                            .after(
                                `<p class='text-danger p-0 m-0 fw-semibold'>
                                ${message}
                            </p>`
                            );
                    });
                    index++;
                });
                $(".error-message").addClass("is-invalid");
            } else {
                $(".invalid").text(
                    xhr.responseJSON.message || "Invalid Credentials"
                );
                $(".error-message").addClass("is-invalid");
                $(".invalid").addClass("text-danger");
            }
        },
        complete: function () {
            $(".sign-in-btn").attr("disabled", false);
            $(".sign-in-btn").removeClass("btn-disabled");
            $(".sign-in-btn").text("Sign In");
        },
    });
}

// Sign Out User
function signOutUser() {
    $.ajax({
        url: "/sign-out",
        type: "POST",
        dataType: "json",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
        },
        success: function (response) {
            alert(response.message);
            // Handle logout (e.g., redirect to login)
        },
        error: function (xhr) {
            alert("Error occurred during sign out.");
        },
    });
}

// Add New Admin
function addNewAdmin(username, email, password, role) {
    $.ajax({
        url: "/add-admin",
        type: "POST",
        dataType: "json",
        data: {
            username: username,
            email: email,
            password: password,
            role: role,
            _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token
        },
        success: function (response) {
            alert(response.message);
            // Handle admin addition (e.g., update admin list)
        },
        error: function (xhr) {
            alert(
                xhr.responseJSON.message || "Error occurred while adding admin."
            );
        },
    });
}

// Bind the sign-up form submission
$(".sign-in-form").on("submit", function (e) {
    e.preventDefault();
    let email = $(".email").val();
    let password = $(".password").val();
    signInUser(email, password);
});

// Similarly, you can bind other functions to their respective forms or buttons.
