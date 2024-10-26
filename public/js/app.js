$(".image-preview").css("display", "none");

$(".update-image").on("input", function (e) {
    const imageUrl = URL.createObjectURL(e.target.files[0]);
    $(".image-preview").css("display", "block").attr("src", imageUrl);
});

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Add assignment
    $(".loading").hide();
    $(".count-loading").hide();
    $(".flash").hide();
    $(document).ready(function () {
        $(".add-assignment")
            .off("click")
            .on("click", function (e) {
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
                        showToast(
                            "Uploaded Successfully!",
                            "Assignment Uploaded Successfully!",
                            "success"
                        );
                        countAssignments();
                        $(".loading").hide();
                        $(".loading-text").show();
                    },

                    error: function (xhr) {
                        if (xhr.status === 422) {
                            showErrorMessages(xhr.responseJSON.errors);
                        }
                    },

                    complete: function () {
                        $(".loading").hide();
                        $(".loading-text").show();
                    },
                });
            });
    });

    // Usage examples:
    // showToast('This is a success message!', 'success');
    // showToast('This is an error message!', 'error');
    // showToast('This is an informational message!', 'info');
    // showToast('This is a warning message!', 'warning');

    // Function to count assignments
    function countAssignments(batch_no) {
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
            window.location.pathname.split("/").includes("teacher") &&
            window.location.pathname.split("/").includes("assignments") &&
            window.location.pathname.split("/").includes("upload")
        ) {
            console.log($(this).find("option:first").val());
            $('select[name="batch_no"]').on("change", function () {
                countAssignments($(this).val());
            });
        }
    });

    // Get assignments
    let allAssignment = []; // Global variable to store all fetched assignments

    // Function to fetch assignments from the server
    function loadStudentAssignments() {
        const userId = window.location.pathname.split("/").pop();
        $(".loader-table").show();
        $(".assignment-table").hide();

        $.ajax({
            url: `/dashboard/student/assignments-get/${userId}`,
            type: "GET",
            success: function (response) {
                allAssignment = response || []; // Store assignments globally
                displayAssignment(allAssignment); // Initially display all assignments
            },
            error: function (xhr) {
                console.error("Error fetching assignments:", xhr.statusText);
            },
            complete: function () {
                $(".loader-table").hide();
                $(".assignment-table").show();
            },
        });
    }

    // Function to display assignments based on the current filter
    function displayAssignment(assignments) {
        const userId = window.location.pathname.split("/").pop();
        console.log(assignments);

        const assignmentsHtml = assignments.assignments
            ?.map((assignment, index) => {
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

                // Check the corresponding status from the status array
                const assignmentStatus = assignments.status[index]?.status; // Assuming it matches the index of the assignment
                const isSubmitted = assignmentStatus === "submitted"; // Check if the assignment is submitted

                return `
            <tr>
                <td class="text-sm">${index + 1}</td>
                <td class="text-sm">${assignment.topic}</td>
                <td class="text-sm">${assignment.max_marks}</td>
                <td class="text-sm">${formattedCreatedAt}</td>
                <td class="text-sm">${formattedCreatedAt}</td>
                <td class="text-sm">${displayFile(assignment?.file)}</td>
                ${
                    isSubmitted
                        ? `<td colspan="5" class="text-center">
                            <i class="bi bi-check-circle-fill text-success"></i> Submitted
                        </td>`
                        : `
                        <td class="text-sm">pending...</td>
                        <td class="text-sm">
                            <form class="upload-form" enctype="multipart/form-data">
                                <input name="assignment_id" type="hidden" value="${assignment.id}">
                                <input name="user_id" type="hidden" value="${userId}">
                                <div class="input-group input-group-sm">
                                    <input name="answer_file" type="file" class="form-control file-input">
                                    <div class="error-message text-danger" style="display: none;"></div>
                                </div>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-purple border-0 btn-disabled p-1 px-2 btn-sm submit-btn" disabled>
                                <img class="loading-submit d-none" src="/assets/images/loading.gif" width="15px" alt="loading">
                                <span>Submit</span>
                            </button>
                        </td>`
                }
            </tr>`;
            })
            .join("");

        $("#assignmentsTableBody").html(assignmentsHtml);
    }

    // Event listener for filter buttons
    $(document).ready(function () {
        // Load assignments when the page is ready
        if (
            window.location.pathname.split("/")[1] === "dashboard" &&
            window.location.pathname.split("/")[2] === "student" &&
            window.location.pathname.split("/")[3] === "assignments"
        ) {
            loadStudentAssignments();
        }

        // Filter buttons functionality
        $(".filter-button-student").on("click", function () {
            const filterType = $(this).data("status");

            // Remove active class from all buttons and add to the clicked one
            $(".filter-button-student").removeClass("btn-purple");
            $(this).addClass("btn-purple");

            // Initialize filtered assignments based on the current state
            let filteredAssignments = allAssignment.assignments; // Access the assignments array
            let filteredStatus = allAssignment.status; // Access the status array

            // Filter assignments based on the selected filter type
            if (filterType === "submitted") {
                filteredAssignments = allAssignment.assignments.filter(
                    (assignment, index) => {
                        return filteredStatus[index]?.status === "submitted"; // Check if the corresponding status is 'submitted'
                    }
                );
            } else if (filterType === "unsubmitted") {
                filteredAssignments = allAssignment.assignments.filter(
                    (assignment, index) => {
                        return filteredStatus[index]?.status !== "submitted"; // Check if the corresponding status is not 'submitted'
                    }
                );
            } else if (filterType === "all") {
                // If the filter is "all", we simply use the original assignments
                filteredAssignments = allAssignment.assignments;
                filteredStatus = allAssignment.status; // Keep all statuses
            }

            // Create a new filtered object to pass to displayAssignment
            const filteredAssignmentsObj = {
                assignments: filteredAssignments,
                status: filteredStatus.filter((_, index) => {
                    return (
                        (filterType === "submitted" &&
                            filteredStatus[index]?.status === "submitted") ||
                        (filterType === "unsubmitted" &&
                            filteredStatus[index]?.status !== "submitted") ||
                        filterType === "all"
                    ); // Include all statuses if filter is "all"
                }),
            };

            // Display filtered assignments
            displayAssignment(filteredAssignmentsObj);
        });
    });

    // // Call the function to load assignments
    // $(document).ready(function () {
    //     if (
    //         window.location.pathname.split("/")[1] == "dashboard" &&
    //         window.location.pathname.split("/")[2] == "student" &&
    //         window.location.pathname.split("/")[3] == "assignments"
    //     ) {
    //         getAssignments();
    //     }
    // });

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
    $(document).ready(function () {
        $(document)
            .off("click")
            .on("click", ".submit-btn", function (e) {
                e.preventDefault();
                const form = $(this).closest("tr").find(".upload-form");
                const input = $(this).closest("tr").find(".file-input");
                const row = $(this).closest("tr");
                const loader = $(this).closest("tr").find(".loading-submit");
                addUploadAssignment(form, input, row, loader);
            });
    });

    // Add assignment upload function
    function addUploadAssignment(form, input, row, loader) {
        let user_id = window.location.pathname.split("/").pop();
        const formData = new FormData(form[0]);
        const errorMessageDiv = form.find(".error-message");
        loader.removeClass("d-none");
        errorMessageDiv.hide().text("");

        $.ajax({
            url: `/dashboard/student/upload-assignment/${user_id}`,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".submit-btn")
                    .prop("disabled", true)
                    .addClass("btn-disabled");
            },
            success: function (response) {
                if (response.status === "success") {
                    updateRowAfterSubmission(row);
                    // showFlashMessage(response.message);
                    showToast(
                        "Uploaded Successfully",
                        response.message,
                        "success"
                    );
                }
            },
            error: function (xhr) {
                showErrorMessages(xhr.responseJSON.errors);
            },
            complete: function () {
                loader.addClass("d-none");
            },
        });
    }

    // Function to update the row after submission
    function updateRowAfterSubmission(row) {
        row.find("td:nth-child(7)").remove(); // Remove 'pending...'
        row.find("td:nth-child(7)").remove(); // Remove form column
        row.find("td:nth-child(7)").remove(); // Remove submit button column
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
            html: "/assets/file_icons/html.png",
            png: "/assets/file_icons/png.webp",
            jpeg: "/assets/file_icons/jpeg.png",
            docx: "/assets/file_icons/docx.webp",
            jpg: "/assets/file_icons/jpg.png",
            default: "/assets/file_icons/default.png",
        };

        return `<a href='${fileUrl}' download>
        <img width='30px' height='30px' src='${
            fileIcons[ext] || fileIcons.default
        }' alt='file icon'>
    </a>`;
    }

    // Get submitted assignments for teacher
    // Get submitted assignments for teacher
    let allAssignments = []; // Store all assignments here

    function getSubmittedAssignments() {
        let user_id = window.location.pathname.split("/").pop();
        // Assume this is your initial load function
        $.ajax({
            url: `/dashboard/teacher/submitted-assignment/${user_id}`,
            type: "GET",
            success: function (response) {
                allAssignments = response; // Store the received data
                displayAssignments(allAssignments); // Display all assignments initially
            },
            error: function (xhr) {
                console.error("Error fetching assignments:", xhr.statusText);
            },
        });
    }

    function displayAssignments(assignments) {
        let assignmentsHtml = assignments
            .map((assignment) => {
                const createdAt = new Date(assignment.assignment?.created_at);
                const options = {
                    timeZone: "Asia/Karachi",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                };
                const formattedDate = createdAt.toLocaleString(
                    "en-US",
                    options
                );
                const day = getDay(createdAt.getDay());

                const studentName = assignment?.user?.name || "N/A";
                const topicName = assignment?.assignment?.topic || "N/A";
                const batchName = assignment?.assignment?.batch_no || "N/A";
                const answerFile = assignment?.answer_file
                    ? displayFile(assignment.answer_file)
                    : "No file";
                const maxMarks = assignment?.assignment?.max_marks || "N/A";
                const obtainedMarks =
                    assignment?.marks !== null
                        ? assignment.marks?.obt_marks
                        : `<input type='number' name='obtainedMarks-${assignment.id}' class='p-0 form-control' placeholder='e.g. 25'>`;

                // Create button dynamically
                const button = $("<button/>", {
                    class: `btn btn-purple btn-sm mark-assignment ${
                        assignment.marks !== null ? "btn-disabled" : ""
                    } `,
                    text: assignment.marks !== null ? "Marked" : "Mark",
                    disabled: assignment.marks !== null,
                    "data-assignment_id": assignment.assignment_id,
                    "data-user_id": assignment?.user?.id,
                    "data-answer_id": assignment.id,
                    "data-max_marks": assignment.assignment?.max_marks,
                });

                return `
                <tr>
                    <td>${formattedDate}</td>
                    <td>${day}</td>
                    <td>${studentName}</td>
                    <td>${topicName}</td>
                    <td>Batch${batchName}</td>
                    <td>${assignment?.assignment?.deadline || "N/A"}</td>
                    <td>${answerFile}</td>
                    <td>${maxMarks}</td>
                    <td class='error-marks small-ph'>${obtainedMarks}</td>
                    <td>${button[0].outerHTML}</td>
                </tr>`;
            })
            .join("");

        $(".submitted-assignments").html(assignmentsHtml);
    }

    $(document).ready(function () {
        // Check the specific URL path before running this code
        if (
            window.location.pathname.split("/").includes("teacher") &&
            window.location.pathname.split("/").includes("assignments") &&
            window.location.pathname.split("/").includes("view")
        ) {
            let allAssignments = []; // Store all assignments after loading by batch

            // Load assignments initially with the first batch option after 1 second
            setTimeout(() => {
                let batch_no = $('select[name="batch_no"]')
                    .find("option:eq(1)")
                    .val();
                getSubmittedAssignments(batch_no);
            }, 1000);

            // Update assignments when batch_no changes
            $('select[name="batch_no"]').on("change", function () {
                getSubmittedAssignments($(this).val());
            });

            // Function to fetch and display assignments based on batch_no
            function getSubmittedAssignments(batch_no) {
                $(".loader-table-teacher").show();

                let user_id = window.location.pathname.split("/").pop();
                $.ajax({
                    url: `/dashboard/teacher/submitted-assignment/${user_id}`,
                    type: "GET",
                    data: {
                        batch_no,
                    },
                    beforeSend: function () {
                        $(".loader-table-teacher").show();
                    },
                    success: function (response) {
                        allAssignments = response; // Store assignments locally
                        displayAssignments(allAssignments); // Display all initially
                        $(".loader-table-teacher").hide();
                    },
                    error: function (xhr) {
                        showErrorMessages(xhr.responseJSON.errors);
                        console.error(
                            "Error fetching assignments:",
                            xhr.statusText
                        );
                        $(".loader-table-teacher").hide();
                    },
                });
            }

            // Function to display assignments
            function displayAssignments(assignments) {
                let assignmentsHtml = assignments
                    .map((assignment) => {
                        const createdAt = new Date(
                            assignment.assignment?.created_at
                        );
                        const options = {
                            timeZone: "Asia/Karachi",
                            month: "2-digit",
                            day: "2-digit",
                            hour: "2-digit",
                            minute: "2-digit",
                        };
                        const formattedDate = createdAt.toLocaleString(
                            "en-US",
                            options
                        );
                        const day = getDay(createdAt.getDay());

                        const studentName = assignment?.user?.name || "N/A";
                        const topicName =
                            assignment?.assignment?.topic || "N/A";
                        const batchName =
                            assignment?.assignment?.batch_no || "N/A";
                        const answerFile = assignment?.answer_file
                            ? displayFile(assignment.answer_file)
                            : "No file";
                        const maxMarks =
                            assignment?.assignment?.max_marks || "N/A";
                        const obtainedMarks =
                            assignment?.marks !== null
                                ? assignment.marks?.obt_marks
                                : `<input type='number' name='obtainedMarks-${assignment.id}' class='p-0 form-control' placeholder='e.g. 25'>`;

                        const button = $("<button/>", {
                            class: `btn btn-purple btn-sm mark-assignment ${
                                assignment.marks !== null ? "btn-disabled" : ""
                            }`,
                            text: assignment.marks !== null ? "Marked" : "Mark",
                            disabled: assignment.marks !== null,
                            "data-assignment_id": assignment.assignment_id,
                            "data-user_id": assignment?.user?.id,
                            "data-answer_id": assignment.id,
                            "data-max_marks": assignment.assignment?.max_marks,
                        });

                        return `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>${day}</td>
                            <td>${studentName}</td>
                            <td>${topicName}</td>
                            <td>Batch ${batchName}</td>
                            <td>${
                                assignment?.assignment?.deadline || "N/A"
                            }</td>
                            <td>${answerFile}</td>
                            <td>${maxMarks}</td>
                            <td class='error-marks small-ph'>${obtainedMarks}</td>
                            <td>${button[0].outerHTML}</td>
                        </tr>`;
                    })
                    .join("");

                $(".submitted-assignments").html(assignmentsHtml);
            }

            // Event listener for filter buttons
            $(".filter-button").on("click", function () {
                const filter = $(this).data("filter");

                let filteredAssignments = allAssignments;
                $(".filter-button").removeClass("btn-purple");
                $(this).addClass("btn-purple");
                if (filter === "marked") {
                    filteredAssignments = allAssignments.filter(
                        (assignment) => assignment.marks !== null
                    );
                } else if (filter === "unmarked") {
                    filteredAssignments = allAssignments.filter(
                        (assignment) => assignment.marks === null
                    );
                }

                displayAssignments(filteredAssignments); // Display filtered data
            });
        }
    });

    // Mark assignments
    $(document).ready(function () {
        if (
            window.location.pathname.split("/").includes("teacher") &&
            window.location.pathname.split("/").includes("assignments") &&
            window.location.pathname.split("/").includes("view")
        ) {
            $(document)
                .off("click", ".mark-assignment")
                .on("click", ".mark-assignment", function () {
                    const $button = $(this); // Reference to the clicked button
                    const answerId = $button.data("answer_id");
                    const obtainedMarks = $(
                        `input[name="obtainedMarks-${answerId}"]`
                    ).val();

                    let data = {
                        assignment_id: $button.data("assignment_id"),
                        answer_id: answerId,
                        user_id: $button.data("user_id"),
                        obt_marks: obtainedMarks,
                        max_marks: $button.data("max_marks"),
                        comments: "salam",
                    };

                    // Show loader and change button text
                    const loaderHtml =
                        '<div class="d-flex gap-1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> </div>';
                    $button.attr("disabled", true).addClass("btn-disabled");

                    $.ajax({
                        url: "/dashboard/teacher/mark-assignment/",
                        type: "POST",
                        data,
                        beforeSend: function () {
                            $button
                                .html(loaderHtml)
                                .attr("disabled", true)
                                .addClass("btn-disabled");
                            $(`input[name="obtainedMarks-${answerId}"]`)
                                .prop("disabled", true)
                                .val("marking...");
                            $(`input[name="obtainedMarks-${answerId}"]`)
                                .siblings(".alert-danger")
                                .remove();
                        },
                        success: function (response) {
                            $button
                                .html("Marked")
                                .attr("disabled", true)
                                .addClass("btn-disabled");
                            showToast(
                                "Marked Successfully",
                                "Assignment marked successfully",
                                "success"
                            );
                            $(`input[name="obtainedMarks-${answerId}"]`)
                                .prop("disabled", true)
                                .val("marked successfully");
                            $(`input[name="obtainedMarks-${answerId}"]`)
                                .siblings(".alert-danger")
                                .remove();
                        },
                        error: function (xhr) {
                            console.error("Error:", xhr);
                            $button
                                .html("Mark")
                                .attr("disabled", false)
                                .removeClass("btn-disabled");
                            // Find the input field related to the current row and show the error message under it
                            const $inputField = $(
                                `input[name="obtainedMarks-${answerId}"]`
                            );

                            // Remove any existing error message to prevent duplicates
                            $inputField.attr("disabled", false);
                            $inputField.siblings(".alert-danger").remove();

                            // Add the error message directly under the input field
                            $inputField.after(`
                        <p class="alert alert-danger text-sm mt-1">
                            ${xhr.responseJSON?.message || xhr.statusText}
                        </p>
                    `);
                        },
                        complete: function () {
                            // Restore button text and hide loader
                            // $button.html('Marked').attr('disabled', true).removeClass('btn-disabled');
                            // getSubmittedAssignments($('input[name="batch_no"]').val()); // Refresh assignments if necessary
                        },
                    });
                });
        }
    });

    // get marks for the student

    $(document).ready(function () {
        $(
            ".loader-table, .course-loading, .teacher-loading, .batch-loading, .error"
        ).hide();
        getMarks();
        if (window.location.pathname.split("/").includes("staff")) {
            getCourses();
            getCoursesTeacher();
            checkFormCompletion();
        }

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
        let user_id = window.location.pathname.split("/").pop();
        $.ajax({
            url: `/dashboard/student/get-marks/${user_id}`,
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
    $(".course-btn")
        .off("click")
        .on("click", function (e) {
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
    $(".teacher-btn")
        .off("click")
        .on("click", function (e) {
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
        if (window.location.pathname.split("/").includes("staff")) {
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
                    data: {
                        course_id,
                        _token: $('input[name="_token"]').val(),
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
                        $('select[name="teacher_assigned"]')
                            .html(teacherOptions)
                            .show(); // Show the select after populating
                    },
                    error: function (xhr) {
                        console.error(xhr.statusText);
                    },
                    complete: function () {
                        $(".teacher-skeleton").hide(); // Hide loading indicator for teachers
                        checkFormValidityBatch();
                    },
                });
            });

            // Check if both course and teacher are selected
            $('select[name="teacher_assigned"]').change(checkFormValidityBatch);

            // Add batch form submission
            $(".batch-btn")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    addBatch();
                });
        }
    });
    // Function to check form validity
    function checkFormValidityBatch() {
        const courseSelected = $('select[name="course_name_batch"]').val();
        const teacherSelected = $('select[name="teacher_assigned"]').val();
        const batchSelected = $('input[name="batch_number"]').val();

        const isValid = courseSelected && teacherSelected && batchSelected;
        $(".batch-btn")
            .attr("disabled", !isValid)
            .toggleClass("btn-disabled", !isValid);
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
                checkFormValidityBatch(); // Check if the form can be submitted again
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
        if (window.location.pathname.split("/").includes("staff")) {
            loadBatches(1); // Load first page of batches on page load
        }

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
                        // alert("Something went wrong, please try again.");
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
                        "<option disabled selected>Select Batch</option>";
                    response.batches.forEach(function (batch) {
                        batchOptions += `<option value="${batch.batch_no}">${batch.batch_no}</option>`;
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

    $(document).ready(function () {
        if (window.location.pathname.split("/").includes("staff")) {
            fetchCourses();
        }
    });
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
            // alert("Error loading students. Please try again.");
            // Handle error if needed (e.g., show error message or retry option)
        },
    });
}

// Fetch and display students
$(document).ready(function () {
    // Initial load of students on page load
    if (window.location.pathname.split("/").includes("staff")) {
        loadStudents(1);
    }

    // Function to load students based on filters (course, batch, and pagination)
    // Load batches when course is selected
    $(document).on("change", ".courses-select", function () {
        let courseId = $(this).val();
        // if (!courseId) return; // Prevent AJAX call if no course is selected
        $(".batch-select").html(
            "<option disabled selected>Loading batches...</option>"
        );
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
            beforeSend: function () {},
            success: function (response) {
                let batchOptions =
                    "<option disabled selected>Select Batch</option>";
                // Populate batch dropdown based on the selected course
                response.options.data.forEach(function (batch) {
                    batchOptions += `<option value="${batch.batch_no}">${batch.batch_no}</option>`;
                });
                $(".batch-select").html(batchOptions); // Update the batch select options
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
                // alert("Error loading batches. Please try again.");
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
    if (window.location.pathname.split("/").includes("staff")) {
        fetchCourses();
    }

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
                // alert("Error loading student details");
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

// count the student assignment and test data

function fetchStudentCounts(id) {
    $(".loading-count").show();
    $.ajax({
        url: `/dashboard/student/get-data-count/${id}`, // The route defined in web.php
        type: "GET",
        success: function (response) {
            $(".loading-count").hide();
            // Extract counts from the response
            const assignmentCount = response.assignments;
            const testCount = response.tests;
            // Update UI elements (assume IDs for displaying counts exist)
            $(".student-assignments").text(assignmentCount);
            $(".student-tests").text(testCount);
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error(
                "An error occurred while fetching the counts:",
                error
            );
        },
    });
}

$(document).ready(function () {
    if (
        window.location.pathname.split("/")[1] == "dashboard" &&
        window.location.pathname.split("/")[2] == "student" &&
        window.location.pathname.split("/")[3] == "home"
    ) {
        let id = window?.location.pathname.split("/").pop();
        fetchStudentCounts(id);
    }
});

$(document).ready(function () {
    let user_id = window.location.pathname.split("/").pop();
    if (window.location.pathname.split("/").includes("teacher")) {
        $.ajax({
            url: `/dashboard/teacher/get-relevent-batches/${user_id}`,
            type: "GET",
            beforeSend: function () {
                $('select[name="batch_no"]').html(`
                    <option disabled selected>Loading Batches...</option>
                    `);
                $('select[name="course_name_teacher"]').html(`
                    <option disabled selected>Loading Courses...</option>
                    `);
            },
            success: function (response) {
                let batchOptions =
                    "<option selected disabled>Select Batch</option>" +
                    response?.batches
                        ?.map(
                            (item) =>
                                `<option value="${item?.batch_no}">Batch ${item.batch_no}</option>`
                        )
                        .join("");
                let courseOptions =
                    "<option selected disabled>Select Course</option>" +
                    response?.courses
                        ?.map(
                            (item) =>
                                `<option value="${item?.id}">Batch ${item?.course_name}</option>`
                        )
                        .join("");
                $('select[name="course_name_teacher"]').html(courseOptions);
                $('select[name="batch_no"]').html(batchOptions);
            },
            error: function (xhr) {
                console.log(xhr.statusText);
            },
        });
    }
});

// get info for relevent batxhes againt a course

$(document).ready(function () {
    if (window.location.pathname.split("/").includes("teacher")) {
        getInfoBatches();
    }
});

function getInfoBatches() {
    $('select[name="course_name_teacher"]').on("change", function () {
        let course_id = $(this).val();
        $.ajax({
            url: "/dashboard/teacher/get-relevent-info-batches",
            type: "POST",
            data: {
                course_id,
            },
            success: function (response) {
                console.log(response);
            },
            error: function (xhr) {
                console.log(xhr.statusText);
            },
        });
    });
}

// get students info for teacher
$(document).ready(function () {
    if (window.location.pathname.split("/").includes("teacher")) {
        getInfoStudents();
    }
});

function getInfoStudents() {
    $('select[name="batch_no"]').on("change", function () {
        $(".loading-strength").show();
        $(".placeholder-text").hide();
        let batch_no = $(this).val();
        let course_no = $('select[name="course_name_teacher"]').val();
        $.ajax({
            url: "/dashboard/teacher/get-relevent-students-info",
            type: "POST",
            data: {
                batch_no,
                course_no,
            },
            beforeSend: function () {
                $(".total-strength").hide();
            },
            success: function (response) {
                $(".total-strength").show();
                $(".total-students").html(response.students);
                $(".loading-strength").hide();
            },
            error: function (xhr) {
                console.log(xhr.statusText);
            },
        });
    });
}

// get stuedents for attendace

$(document).ready(function () {
    let user_id = window.location.pathname.split("/").pop();

    if (
        window.location.pathname.split("/").includes("teacher") &&
        window.location.pathname.split("/").includes("attendance") &&
        window.location.pathname.split("/").includes("mark")
    ) {
        $(".loader-table-teacher").hide();

        // When the 'batch_no' is changed
        $('select[name="batch_no"]').on("change", function () {
            let batch_no = $(this).val(); // Get the selected batch number
            let course_name = $("select[name='course_name_teacher']").val(); // Get the selected course name
            $(".teacher-attendance-mark-table").hide();
            $(".loader-table-teacher").show();

            $.ajax({
                url: `/dashboard/teacher/show-students/${user_id}`,
                type: "GET",
                data: {
                    batch_no:
                        batch_no ||
                        $("select[name='batch_no']").find("option:first").val(),
                    course_name:
                        course_name ||
                        $("select[name='course_name_teacher']")
                            .find("option:first")
                            .val(),
                },
                success: function (response) {
                    console.log(response);
                    let rowsHtml = response?.students
                        ?.map((student) => {
                            return `
                            <tr>
                                <td id='slice-name' >${
                                    student.name && student.name.length > 10
                                        ? student.name.slice(0, 10) + "..."
                                        : student.name
                                }</td>
                                <td class='fw-semibold ${
                                    student?.attendance_percentage >= 75
                                        ? "text-success"
                                        : "text-danger"
                                }' >
                                    ${student?.attendance_percentage}%
                                </td>
                                <td>
                                    <input type="radio" name="attendance_${
                                        student.id
                                    }" value="present" />
                                </td>
                                <td>
                                    <input type="radio" name="attendance_${
                                        student.id
                                    }" value="absent" />
                                </td>
                                <td>
                                    <input type="radio" name="attendance_${
                                        student.id
                                    }" value="leave" />
                                </td>
                                <td>
                                    <div class='input-group input-group-sm'>
                                        <input type="text" name="remarks_${
                                            student.id
                                        }" class="form-control rounded-pill input-group-sm" placeholder="Enter remarks" />
                                    </div>
                                </td>
                            </tr>
                        `;
                        })
                        .join("");

                    // Append generated rows to the table body
                    $(".teacher-mark-attendace").html(rowsHtml);
                    $(".teacher-attendance-mark-table").show();
                    $(".loader-table-teacher").hide();
                },
                error: function (xhr) {
                    console.log(xhr.statusText);
                },
            });
        });
    }

    // Submit the attendance
    $(".att-mark-btn")
        .off("click")
        .on("click", function (e) {
            e.preventDefault();
            let loader = $(".attendace-loading-gif");

            let user_id = window.location.pathname.split("/").pop();
            // Collect the attendance data for each student
            let attendanceData = [];
            let topic = $(`input[name="topic_name"]`).val();
            $("tbody.teacher-mark-attendace tr").each(function () {
                let studentId = $(this)
                    .find('input[type="radio"][name^="attendance_"]')
                    .attr("name")
                    .split("_")[1];
                let attendance = $(this)
                    .find(
                        `input[type="radio"][name="attendance_${studentId}"]:checked`
                    )
                    .val();
                let remarks = $(`input[name="remarks_${studentId}"]`).val();

                if (attendance) {
                    attendanceData.push({
                        student_id: studentId,
                        attendance: attendance,
                        remarks: remarks,
                        topic: topic,
                    });
                }
            });

            // Send the data to the backend
            $.ajax({
                url: `/dashboard/teacher/submit-attendance/${user_id}`,
                type: "POST",
                data: {
                    batch_no: $("select[name='batch_no']").val(),
                    course_name: $("select[name='course_name_teacher']").val(),
                    attendance: attendanceData,
                    _token: $('meta[name="csrf-token"]').attr("content"), // Ensure CSRF token is sent
                },
                beforeSend: function () {
                    $(".att-mark-btn")
                        .html(
                            `<div class="spinner-border  " style="width:25px;height:25px" role="status">
  <span class="visually-hidden">Loading...</span>
</div> `
                        )
                        .prop("disabled", true)
                        .addClass("btn-disabled"); // Disable the button while loading
                },
                success: function (response) {
                    alert("Attendance submitted successfully!");
                    $(".att-mark-btn").prop("disabled", false);
                },
                error: function (xhr) {
                    console.log(xhr.responseJSON);

                    showErrorMessages(xhr.responseJSON.errors);

                    $(".att-mark-btn")
                        .prop("disabled", false)
                        .removeClass("btn-disabled"); // Re-enable the button after completion
                },
                complete: function () {
                    $(".att-mark-btn").prop("disabled", false); // Re-enable the button after completion
                    // Optionally reset the button text
                    $(".att-mark-btn").html("Submit Attendance");
                },
            });
        });
});

function sliceName(name) {
    if (name.length > 10) {
        return name.slice(0, 10) + "...";
    } else {
        return name;
    }
}

// check if the attendace has been marked for the current date

$(document).ready(function () {
    let user_id = window.location.pathname.split("/").pop();

    // Check if attendance is already marked when loading the page or when batch/course is selected
    function checkAttendanceMarked(batch_no, course_name) {
        $.ajax({
            url: `/dashboard/teacher/check-attendance-marked/${user_id}`,
            type: "GET",
            data: {
                batch_no: batch_no,
                course_name: course_name,
            },
            success: function (response) {
                if (response.attendance_marked) {
                    // Disable the button if attendance has already been marked
                    $(".att-mark-btn")
                        .prop("disabled", true)
                        .text("Attendance Already Marked")
                        .addClass("btn-disabled");
                } else {
                    // Enable the button if no attendance is marked for today
                    $(".att-mark-btn")
                        .prop("disabled", false)
                        .text("Submit Attendance")
                        .removeClass("btn-disabled");
                }
            },
            error: function (xhr) {
                console.error("Error checking attendance:", xhr.statusText);
            },
        });
    }

    // Example usage: Call the function when a batch or course is selected or on page load
    $('select[name="batch_no"], select[name="course_name_teacher"]').on(
        "change",
        function () {
            let batch_no = $('select[name="batch_no"]').val();
            let course_name = $('select[name="course_name_teacher"]').val();

            checkAttendanceMarked(batch_no, course_name); // Call to check if attendance is already marked
        }
    );

    // Initial check when the page loads
    setTimeout(() => {
        let batch_no = $('select[name="batch_no"]').find("option:eq(1)").val();
        let course_name = $('select[name="course_name_teacher"]')
            .find("option:eq(1)")
            .val();
        checkAttendanceMarked(batch_no, course_name);
    }, 1000);
});

// handle the error showing

function showErrorMessages(errors) {
    // Show the error container and add the flex class for display
    $(".error").show().addClass("d-flex");

    // Clear any existing messages
    $(".error__title").empty();

    // Check if errors is an object
    if (typeof errors === "object" && errors !== null) {
        for (let key in errors) {
            if (errors.hasOwnProperty(key)) {
                // Check if the property is an array
                if (Array.isArray(errors[key])) {
                    // Iterate over the array of errors
                    errors[key].forEach(function (item) {
                        $(".error__title").append(
                            `<li class='fw-bold alert-danger'>${item}</li>`
                        );
                    });
                } else if (typeof errors[key] === "string") {
                    // If it's a single error string, display it
                    $(".error__title").append(
                        `<li class='fw-bold alert-danger'>${errors[key]}</li>`
                    );
                }
            }
        }
    } else if (typeof errors === "string") {
        // If errors is a single error string
        $(".error__title").append(
            `<li class='fw-bold alert-danger'>${errors}</li>`
        );
    } else {
        // Fallback in case of unexpected error structure
        $(".error__title").append(
            `<li class='fw-bold alert-danger'>Unexpected error occurred.</li>`
        );
    }

    // Optionally show the message box with a slight delay
    setTimeout(() => {
        $(".message-box").css("transform", "translate(-50%,-50%)");
        $(".message-box").css("opacity", "1");
    }, 100);
}

// close error message

function closeErrorMessages() {
    // Optionally, animate the closing of the error message
    $(".error").fadeOut(300, function () {
        // Reset styles or clear the error messages after hiding
        $(".error__title").empty();
        $(".error").removeClass("d-flex"); // Remove the flex class
        $(".message-box").css("transform", "translate(-50%,-20%)");
        $(".message-box").css("opacity", "0");
    });
}

// count total classes

function countTotalClasses() {
    // let batch_no = $('select[name="batch_no"]').val();
    // let course_name = $('select[name="course_name_teacher"]').val();
    let user_id = window.location.pathname.split("/").pop();
    $.ajax({
        url: `/dashboard/student/get-total-classes/${user_id}`,
        type: "GET",
        // data: {
        //     batch_no,
        //     course_name,
        // },
        beforeSend: function () {},
        success: function (response) {
            console.log(response);
            $(".student-lessons").html(response?.totalClasses);
            $(".total-classes-student").html(
                `Total(${response?.totalClasses})`
            );
            $(".total-present-student").html(`Presents(${response?.presents})`);
            $(".total-absent-student").html(`Absents(${response?.absents})`);
        },
        error: function (xhr) {
            console.log(xhr.statusText);
        },
    });
}

$(document).ready(function () {
    if (
        window.location.pathname.split("/").includes("student") &&
        window.location.pathname.split("/").includes("home") &&
        window.location.pathname.split("/").includes("dashboard")
    ) {
        countTotalClasses();
    }
    // countTotalClasses();
});

function attendanceRecord() {
    // let batch_no = $('select[name="batch_no"]').val();
    // let course_name = $('select[name="course_name_teacher"]').val();
    let user_id = window.location.pathname.split("/").pop();
    $.ajax({
        url: `/dashboard/student/get-attendance-record/${user_id}`,
        type: "GET",
        // data: {
        //     batch_no,
        //     course_name,
        // },
        success: function (response) {
            // console.log(response)
            // $('.student-lessons').html(response?.totalClasses)
            let records = "";

            response?.attendance?.forEach((item, index) => {
                let createdAt = new Date(item?.created_at);
                records += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item?.attendance_date}</td>
                        <td>${getCurrentDay(createdAt.getDay())}</td>
                        <td>${item?.topic}</td>
                        <td>${item?.remarks || "No remarks"}</td>
                        <td>${item?.status}</td>
                    </tr>
                `;
            });

            $(".student-attendance-table").html(records);
        },
        error: function (xhr) {
            console.log(xhr.statusText);
        },
    });
}

$(document).ready(function () {
    if (
        window.location.pathname.split("/").includes("student") &&
        window.location.pathname.split("/").includes("attendance") &&
        window.location.pathname.split("/").includes("dashboard")
    ) {
        attendanceRecord();
        countTotalClasses();
    }
});
function getCurrentDay(day) {
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

// get the attendace for teacher

$(document).ready(function () {
    if (
        window.location.pathname.split("/").includes("teacher") &&
        window.location.pathname.split("/").includes("attendance") &&
        window.location.pathname.split("/").includes("view")
    ) {
        let user_id = window.location.pathname.split("/").pop();
        setTimeout(() => {
            let batch_no = $("select[name='batch_no']")
                .find("option:eq(1)")
                .val();
            let course_name = $("select[name='course_name_teacher']")
                .find("option:eq(1)")
                .val();
            getStudentAttendance(batch_no, course_name);
            attendanceChartTeacher(user_id, batch_no, course_name);
        }, 1000);

        $(".loader-table-teacher").hide();

        // When the 'batch_no' is changed
        $('select[name="batch_no"]').on("change", function () {
            let batch_no = $(this).val(); // Get the selected batch number
            let course_name = $("select[name='course_name_teacher']")
                .find("option:eq(1)")
                .val(); // Get the selected course name
            $(".loader-table-teacher").show();

            getStudentAttendance(batch_no, course_name);
            attendanceChartTeacher(user_id, batch_no, course_name);
        });
    }
});

function getStudentAttendance(batch_no, course_name) {
    let user_id = window.location.pathname.split("/").pop();

    $.ajax({
        url: `/dashboard/teacher/show-students/${user_id}`,
        type: "GET",
        data: {
            batch_no: batch_no,
            course_name: course_name,
        },
        success: function (response) {
            studentAttendance(response);

            $(".view-att")
                .off("click")
                .on("click", function () {
                    const user_id = $(this).data("id");
                    showAttendance(user_id);
                });
        },
        error: function (xhr) {
            console.log(xhr.statusText);
        },
    });
}

function studentAttendance(response) {
    let rowsHtml = response?.students

        ?.map((student, index) => {
            return `
                            <tr>
                            <td>${index + 1}</td>
                                <td id='slice-name' >${
                                    student.name && student.name.length > 10
                                        ? student.name.slice(0, 10) + "..."
                                        : student.name
                                }</td>
                                <td class='fw-semibold ${
                                    student?.attendance_percentage >= 75
                                        ? "text-success"
                                        : "text-danger"
                                }' >
                                    ${student?.attendance_percentage}%
                                </td>

                                <td>
                                   <button
                                    type='button'
                                    data-bs-toggle='modal'
                                    data-bs-target='#attModal'
                                    data-id='${student?.id}'
                                    class="btn btn-sm view-att btn-purple">
                                    View
                                </button>
                                </td>
                            </tr>
                        `;
        })
        .join("");

    // Append generated rows to the table body
    $(".student-attendance-teacher").html(rowsHtml);
    $(".teacher-attendance-mark-table").show();
    $(".loader-table-teacher").hide();
}

function showAttendance(user_id) {
    $(".loader-table-teacher").show();

    $.ajax({
        url: `/dashboard/teacher/student/attendance/${user_id}`,
        method: "GET",
        success: function (response) {
            console.log(response);
            // Populate modal with fetched data
            $("#attModalLabel").text(`${response.student.name} - Attendance`);

            let attendanceHtml = response.attendanceRecords
                .map(
                    (record) => `
                    <tr>
                        <td>${record.attendance_date}</td>
                        <td>${new Date(
                            record.attendance_date
                        ).toLocaleDateString("en-US", { weekday: "long" })}</td>
                        <td>${record.topic}</td>
                        <td>${record.remarks || "No remarks"}</td>
                        <td class="${
                            record.status === "present"
                                ? "text-success"
                                : "text-danger"
                        }">
                            ${
                                record.status.charAt(0).toUpperCase() +
                                record.status.slice(1)
                            }
                        </td>
                        <td>
                            <select class="form-select form-select-sm attendance-status" data-record-id="${
                                record.id
                            }">
                                <option value="present" ${
                                    record.status === "present"
                                        ? "selected"
                                        : ""
                                }>Present</option>
                                <option value="leave" ${
                                    record.status === "leave" ? "selected" : ""
                                }>Leave</option>
                                <option value="absent" ${
                                    record.status === "absent" ? "selected" : ""
                                }>Absent</option>
                            </select>
                        </td>
                    </tr>
                `
                )
                .join("");

            $(".student-attendance-table").html(attendanceHtml);
            $(".loader-table-teacher").hide();
        },
        error: function () {
            alert("Failed to fetch attendance records");
        },
    });
}

$(document).ready(function () {
    if (
        window.location.pathname.split("/").includes("teacher") &&
        window.location.pathname.split("/").includes("attendance") &&
        window.location.pathname.split("/").includes("view")
    ) {
        $(document)
            .off("change")
            .on("change", ".attendance-status", function () {
                $(".att-loading").show();
                $(".att-loading-btn")
                    .prop("disabled", true)
                    .addClass(
                        "btn-disabled"
                    ).html(`<div style='width:20px;height:20px;' class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                    </div>`);
                const recordId = $(this).data("record-id");
                const newStatus = $(this).val();
                $(".spinner-border").show();

                $.ajax({
                    url: `/dashboard/teacher/student/attendance/update/${recordId}`,
                    method: "POST",
                    data: {
                        status: newStatus,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },

                    success: function () {
                        $(".att-loading").hide();
                        $(".att-loading-btn")
                            .prop("disabled", false)
                            .removeClass("btn-disabled")
                            .text("Close");
                        showToast(
                            "Updated Status",
                            "Attendance status updated successfully",
                            "success"
                        );
                    },
                    complete: function () {
                        $(".att-loading").hide();
                        $(".att-loading-btn")
                            .prop("disabled", false)
                            .removeClass("btn-disabled")
                            .text("Close");
                    },
                    error: function (xhr) {
                        showErrorMessages(xhr.responseJSON.errors);
                        $(".att-loading").hide();
                        $(".att-loading-btn")
                            .prop("disabled", false)
                            .removeClass("btn-disabled")
                            .text("Close");
                        alert("Attendance status updated successfully");
                    },
                });
            });
    }
});

// toast message

function showToast(title, message, type) {
    $("#toast-title").text(title);
    $("#toast-message").text(message);

    // Remove previous toast type classes and add new one based on type
    $("#toast")
        .removeClass("toast-success toast-warning toast-error")
        .addClass(`toast-${type}`);

    $("#toast")
        .stop(true, true) // Stop any ongoing animations
        .slideDown(300) // Slide down to show the toast
        .delay(4000) // Wait for 4 seconds
        .slideUp(300); // Slide up to hide the toast

    // Add close button functionality
    $("#toast-close")
        .off("click")
        .on("click", function () {
            $("#toast").stop(true, true).slideUp(300); // Slide up on close
        });
}

// $(document).ready(function () {
//     if (
//         window.location.pathname.split("/").includes("teacher") &&
//         window.location.pathname.split("/").includes("attendance") &&
//         window.location.pathname.split("/").includes("view")
//     ) {
//         let user_id = window.location.pathname.split("/").pop();

//         // Course select change event
//         $("select[name='course_name_teacher']")
//             .off("change")
//             .on("change", function () {
//                 let course_id = $(this).val();

//                 // Batch select change event
//                 $("select[name='batch_no']")
//                     .off("change")
//                     .on("change", function () {
//                         let batch_no = $(this).val();
//                         attendanceChartTeacher(user_id, batch_no, course_id);
//                     });
//             });
//     }
// });

function attendanceChartTeacher(user_id, batch_no, course_id) {
    $.ajax({
        url: `/dashboard/teacher/attendance/data/${user_id}`,
        type: "GET",
        data: {
            batch_no: batch_no,
            course_id: course_id,
        },
        success: function (response) {
            console.log("Data loaded successfully:", response);
            updateCharts(response);
        },
        error: function (xhr) {
            console.error("Error loading data:", xhr);
        },
    });
}

let pieChart;
let doughnutChart;

function updateCharts(response) {
    const presentsCount = response.presentsCount;
    const absentsCount = response.absentsCount;

    // Update the charts with the new data
    if (pieChart) {
        pieChart.data.datasets[0].data = [presentsCount, absentsCount];
        pieChart.update();
    } else {
        createCharts(presentsCount, absentsCount);
    }

    if (doughnutChart) {
        doughnutChart.data.datasets[0].data = [presentsCount, absentsCount];
        doughnutChart.update();
    } else {
        createCharts(presentsCount, absentsCount);
    }
}

function createCharts(presentsCount, absentsCount) {
    const ctxPie = document.getElementById("pieChartCanvas").getContext("2d");
    const ctxDoughnut = document
        .getElementById("doughnutChartCanvas")
        .getContext("2d");

    pieChart = new Chart(ctxPie, {
        type: "pie",
        data: {
            labels: ["Presents", "Absents"],
            datasets: [
                {
                    data: [presentsCount, absentsCount],
                    backgroundColor: ["green", "red"],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
        },
    });

    doughnutChart = new Chart(ctxDoughnut, {
        type: "doughnut",
        data: {
            labels: ["Presents", "Absents"],
            datasets: [
                {
                    data: [presentsCount, absentsCount],
                    backgroundColor: ["green", "red"],
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
        },
    });
}
