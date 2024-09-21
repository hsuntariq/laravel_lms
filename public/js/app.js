// user image update preview
$(".image-preview").css("display", "none");

$(".update-image").on("input", function (e) {
    let imageUrl = URL.createObjectURL(e.target.files[0]);
    $(".image-preview").css("display", "block");
    $(".image-preview").attr("src", imageUrl);
});

// add assignment
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
        // headers: {
        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // },

        beforeSend: function () {
            // Clear previous errors
            $(".text-danger").remove();
        },

        success: function (response) {
            $(".assignment-data")[0].reset();
            $(".flash").show();
            $(".notificationPara").html(response.message);
            $(".AllowBtn").click(function () {
                $(".flash").fadeOut();
            });
            countAssignments();
        },
        error: function (xhr, response, error) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let errorElement =
                        '<p class="text-danger fw-medium    m-0">' +
                        value[0] +
                        "</p>";
                    $(
                        'input[name="' + key + '"], select[name="' + key + '"]'
                    ).after(errorElement);
                });
            }
        },
        complete: function () {
            $(".loading").hide();
            $(".loading-text").show();
        },
    });
});

// function to count assignments
function countAssignments() {
    let batch_no =
        $('[name="batch_no"]').val() ||
        $('[name="batch_no"]').find("option:first").val();
    // console.log(batch_no)
    $(".count-loading").show();
    $(".total-assignments").hide();
    $(".total-tests").hide();

    $.ajax({
        url: "/dashboard/teacher/assignment-count", // Your correct route URL
        type: "GET",
        data: {
            batch_no: batch_no,
        }, // Send data as an object
        success: function (response) {
            $(".total-assignments").html(response.assignments);
            $(".total-tests").html(response.tests);
        },
        error: function (xhr, response, error) {
            console.error("Error fetching assignment count:", xhr.statusText);
        },
        complete: function () {
            $(".count-loading").hide();
            $(".total-assignments").show();
            $(".total-tests").show();
        },
    });
}

// count asssignments
$(document).ready(function () {
    countAssignments();
    // Listen for input changes on the select dropdown
    $('[name="batch_no"]').on("input", function () {
        countAssignments();
    });
});

// get assignments

function getAssignments() {
    let arr = window.location.pathname.split("/");
    let batch_no = arr[arr.length - 1];
    $(".loader-table").show();
    $(".assignment-table").hide();

    $.ajax({
        url: `/dashboard/student/assignments-get/${batch_no}`,
        type: "GET",

        success: function (response) {
            let assignmentsHtml = "";

            response.forEach((assignment, index) => {
                // Convert the created_at timestamp to a Date object
                const createdAtDate = new Date(assignment.created_at);

                // Define options for PKT time zone
                const options = {
                    timeZone: "Asia/Karachi",
                    month: "2-digit",
                    day: "2-digit",
                    hour: "2-digit",
                    minute: "2-digit",
                };

                // Format the date to PKT
                const formattedCreatedAt = createdAtDate.toLocaleString(
                    "en-US",
                    options
                );

                assignmentsHtml += `
            <tr>
    <td class="text-sm">${index + 1}</td>
    <td class="text-sm">${assignment.topic}</td>
    <td class="text-sm">${assignment.max_marks}</td>
    <td class="text-sm">${formattedCreatedAt}</td>
    <td class="text-sm">${formattedCreatedAt}</td>

    ${
        assignment?.answers?.length > 0
            ? `
        <td colspan="4" class="text-center">
            <i class="bi bi-check-circle-fill text-success"></i> Submitted
        </td>
    `
            : `
        <td class="text-sm">pending...</td>
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
                <img class="loading-submit d-none"
                    src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                    width="20px" alt="Assignmate loading"> <span>
                    Submit
                    </span>
            </button>
        </td>
    `
    }
</tr>`;
            });

            // Insert the dynamically generated rows into the table body
            $("#assignmentsTableBody").html(assignmentsHtml);
        },
        error: function (xhr, response, error) {
            console.error("Error:", xhr.statusText);
        },
        complete: function () {
            $(".loader-table").hide();
            $(".assignment-table").show();
        },
    });
}

// Call the function to load assignments
getAssignments();

// get the status of the assignments

function getAssignmentStatus() {
    $(document).ready(function () {
        $.ajax({
            url: "/dashboard/student/get-status",
            type: "GET",
            success: function (response) {
                // console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(chr.statusText);
            },
        });
    });
}

// Attach event listeners to file inputs and submit buttons
$(document).on("change", ".file-input", function () {
    let submitBtn = $(this).closest("tr").find(".submit-btn");
    if ($(this).val()) {
        submitBtn.removeAttr("disabled");
        submitBtn.removeClass("btn-disabled");
    } else {
        submitBtn.addClass("btn-disabled");
        submitBtn.attr("disabled", "disabled");
    }
});

$(document).on("click", ".submit-btn", function (e) {
    e.preventDefault();
    let form = $(this).closest("tr").find(".upload-form");
    let input = $(this).closest("tr").find(".file-input");
    let row = $(this).closest("tr");
    let loader = $(this).closest("tr").find(".loading-submit");
    addUploadAssignment(form, input, row, loader); // Pass form and input
});

// add assignment
function addUploadAssignment(form, input, row, loader) {
    let formData = new FormData(form[0]); // Ensure the FormData object is constructed from the correct form
    let errorMessageDiv = form.find(".error-message");
    loader.removeClass("d-none");
    // Clear previous errors
    errorMessageDiv.hide().text("");

    $.ajax({
        url: `/dashboard/student/upload-assignment`, // Ensure this route exists and is correct
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === "success") {
                // Update the row to reflect the "Submitted" status
                row.find("td:nth-child(6)").remove(); // Remove 'pending...'
                row.find("td:nth-child(6)").remove(); // Remove form column
                row.find("td:nth-child(6)").remove(); // Remove submit button column

                // Append the 'Submitted' status
                row.append(`
                    <td colspan="3" class="text-center">
                        <i class="bi bi-check-circle-fill text-success"></i> Submitted
                    </td>
                `);
                $(".flash").show();
                $(".notificationPara").html(response.message);
                $(".AllowBtn").click(function () {
                    $(".flash").fadeOut();
                });
            }
        },
        error: function (xhr, response, error) {
            console.error("Error:", xhr.statusText);
            if (xhr.status === 422) {
                // Display validation errors
                let errors = xhr.responseJSON.errors;
                if (errors && errors.answer_file) {
                    errorMessageDiv.text(errors.answer_file[0]).show();
                }
            } else {
                alert("Failed to upload the assignment");
            }
        },
        complete: function () {
            loader.addClass("d-none");
        },
    });
}

// get the related day
function getDay(day) {
    switch (day) {
        case 0:
            return "Sunday";
        case 1:
            return "Monday";
        case 2:
            return "Tuesday";
        case 3:
            return "Wednesday";
        case 4:
            return "Thursday";
        case 5:
            return "Friday";
        case 6:
            return "Saturday";
        default:
            break;
    }
}

// get assignment format
function displayFile(file) {
    const ext = file?.split(".").pop();
    const fileUrl = `/storage/${file}`;
    switch (ext) {
        case "html":
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJjgIqyxy6qt5DqMSA4gKCwBHeAnscg9o5dQ&s' alt='file icon' >
            </a>`;
        case "png":
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://cdn-icons-png.freepik.com/256/10511/10511554.png?semt=ais_hybrid' alt='file icon' >
            </a>`;
        case "jpeg":
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://cdn-icons-png.flaticon.com/256/6716/6716932.png' alt='file icon' >
            </a>`;
        case "docx":
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://cdn.iconscout.com/icon/free/png-256/free-docx-file-icon-download-in-svg-png-gif-formats--format-document-extension-pack-files-folders-icons-504256.png?f=webp&w=256' alt='file icon' >
            </a>`;
        case "jpg":
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://cdn-icons-png.freepik.com/256/136/136524.png' alt='file icon' >
            </a>`;

        default:
            return `<a href='${fileUrl}' download>
                <img width='30px' height='30px' src='https://3149836655-files.gitbook.io/~/files/v0/b/gitbook-legacy-files/o/spaces%2F-M8KDxOujDoPpJyJJ5_i%2Favatar-1590579241040.png?generation=1590579241552005&alt=media' alt='file icon' >
            </a>`;
    }
}

// get submitted assignments for teacher

function getSubmittedAssignments() {
    $(".loader-table").show();
    let batch_no =
        $('[name="batch_no"]').val() ||
        $('[name="batch_no"]').find("option:first").val();
    $.ajax({
        url: "/dashboard/teacher/submitted-assignment",
        type: "GET",
        data: { batch_no: batch_no },
        success: function (response) {
            let submittedAssignments = "";
            response?.forEach(function (assignment, index) {
                console.log(assignment);

                // Check if the assignment is already marked
                const isMarked = assignment?.marked;

                submittedAssignments += `
                <tr>
                    <td class="text-sm">${new Date(
                        assignment?.created_at
                    ).getDate()}/${new Date(
                    assignment?.created_at
                ).getMonth()}</td>
                    <td class="text-sm">${getDay(
                        new Date(assignment?.created_at).getDay()
                    )}</td>
                    <td class="text-sm">${assignment?.user?.name}</td>
                    <td class="text-sm">${assignment?.assignment?.deadline}</td>
                    <td class="text-sm">${displayFile(
                        assignment?.answer_file
                    )}</td>
                    <td class="text-sm">${
                        assignment?.assignment?.max_marks
                    }</td>`;

                // Only show the input and button if the assignment is not marked
                if (!isMarked) {
                    submittedAssignments += `
                    <td class="text-sm input-group-sm">
                        <input type='number' class='form-control obtained-marks' placeholder='Obtained marks' />
                    </td>
                    <td>
                        <button class='btn mark-assignment-btn btn-sm btn-purple'
                            data-assignment_id='${assignment?.assignment?.id}'
                            data-answer_id='${assignment?.id}'
                            data-user_id='${assignment?.user?.id}'
                            data-max_marks='${assignment?.assignment?.max_marks}'
                        >
                            Mark
                        </button>
                    </td>`;
                } else {
                    // Show a message or icon indicating it's already marked
                    submittedAssignments += `
                    <td class="text-sm">Already marked <span class='fw-bold'>(${assignment?.marks?.obt_marks}/${assignment?.marks?.max_marks})</span></td>
                    <td>
                        <button class='btn btn-sm btn-purple'>
                            Update
                        </button>
                    </td>`; // Empty cell for alignment
                }

                submittedAssignments += `</tr>`;
            });
            $(".submitted-assignments").html(submittedAssignments);
        },
        error: function (xhr, status, error) {
            console.log(xhr.statusText);
        },
        complete: function () {
            $(".loader-table").hide();
        },
    });
}

$(document).ready(function () {
    getSubmittedAssignments();
    // Listen for input changes on the select dropdown
    $('[name="batch_no"]').on("input", function () {
        getSubmittedAssignments();
    });
});

// Mark assignments
$(document).on("click", ".mark-assignment-btn", function () {
    let assignment_id = $(this).data("assignment_id");
    let answer_id = $(this).data("answer_id");
    let max_marks = $(this).data("max_marks");
    let user_id = $(this).data("user_id");
    let obtainedMarks = $(this).closest("tr").find(".obtained-marks").val();

    if (!obtainedMarks || obtainedMarks < 0 || obtainedMarks > max_marks) {
        alert("Please enter valid marks.");
        return;
    }

    $.ajax({
        url: "/dashboard/teacher/mark-assignment", // route to mark assignment
        type: "POST",
        data: {
            assignment_id: assignment_id,
            answer_id: answer_id,
            user_id: user_id,
            obt_marks: obtainedMarks,
            comments: "Assignment marked.",
            max_marks: max_marks,
        },
        success: function (response) {
            if (response.status === "success") {
                alert(response.message);
                // Refresh the assignments list after marking
                getSubmittedAssignments();
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            alert("Failed to mark the assignment.");
        },
    });
});

// get marks for the student

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
                // Display message when no assignments are found for both tables
                assignmentTableBody = `<tr>
                    <td colspan="8" class="text-center text-sm">No assignments marked yet</td>
                </tr>`;
                testTableBody = `<tr>
                    <td colspan="8" class="text-center text-sm">No tests marked yet</td>
                </tr>`;
            } else {
                response.forEach(function (mark) {
                    const createdAt = new Date(mark.answer?.created_at);
                    const day = createdAt.toLocaleDateString("en-US", {
                        weekday: "long",
                    });
                    const date = `${createdAt.getDate()}/${
                        createdAt.getMonth() + 1
                    }/${createdAt.getFullYear()}`;
                    const time = `${createdAt.getHours()}:${createdAt.getMinutes()}`;
                    const rowHtml = `
                        <tr>
                            <td class="text-sm">${date}</td>
                            <td class="text-sm">${day}</td>
                            <td class="text-sm">${
                                mark.student?.name || "N/A"
                            }</td>
                            <td class="text-sm">${time}</td>
                            <td class="text-sm">${displayFile(
                                mark.answer?.answer_file
                            )}</td>
                            <td class="text-sm">${mark?.max_marks || "N/A"}</td>
                            <td class="text-sm">${mark.obt_marks || "N/A"}</td>
                        </tr>`;

                    // Check if it's an assignment or a test based on the type
                    if (mark?.answer?.assignment?.type == "assignment") {
                        assignmentTableBody += rowHtml;
                    } else {
                        testTableBody += rowHtml;
                    }
                });
            }

            // Populate the tables with the respective content
            $(".marks-table").html(assignmentTableBody);
            $(".marks-test-table").html(testTableBody);
        },
        error: function (xhr, status, error) {
            console.log(xhr.statusText);
        },
        complete: function () {
            $(".loader-table").hide();
            $(".hide-table").show();
        },
    });
}

$(document).ready(function () {
    getMarks();
});

// add course

$(document).ready(function () {
    $(".course-loading").hide();
    $(".error").hide();
});

function addCourse() {
    $(".course-loading").show();
    $(".course-btn").attr("disabled", "disabled");
    $(".course-btn").addClass("btn-disabled");
    let data = $(".course-form").serialize();
    // console.log(data)
    $.ajax({
        url: "/dashboard/staff/add-course-data",
        type: "POST",
        data: $(".course-form").serialize(),
        success: function (response) {
            $(".flash").show();
            $(".notificationPara").html("Course Added Successfully");
            $(".AllowBtn").click(function () {
                $(".flash").fadeOut();
            });
        },
        error: function (xhr, status, error) {
            if (xhr.status == 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let errorElement =
                        '<p class="text-danger fw-medium    m-0">' +
                        value[0] +
                        "</p>";
                    $('input[name="' + key + '"]').after(errorElement);
                });
            }

            if (xhr.status == 400) {
                $(".error").show();
                $(".error__title").html("Course already present!");
                $(".error__close").click(function () {
                    $(".error").fadeOut();
                });
            }
        },
        complete: function () {
            $(".course-loading").hide();
            $(".course-btn").removeAttr("disabled");
            $(".course-btn").removeClass("btn-disabled");
            $(".course-form")[0].reset();
        },
    });
}

$(".course-btn").click(function (e) {
    e.preventDefault();
    addCourse();
});

// get course data
function getCourses() {
    $(".courses-table").hide();

    $.ajax({
        url: "/dashboard/staff/get-course-data",
        type: "GET",
        success: function (response) {
            let courses = "";
            response.forEach(function (course, index) {
                courses += `
                    <tr>
                        <td>${course?.id}</td>
                        <td>${course?.course_name}</td>
                        <td>${course?.course_duration} months</td>
                        <td>Rs.${course?.course_fee} </td>
                        <td>
                            <button class='btn btn-danger delete-course'>
                                Delete
                            </button>
                        </td>
                        <td>
                            <button class='btn btn-purple delete-course'>
                                Update
                            </button>
                        </td>
                    </tr>
                `;
            });

            $(".courses").html(courses);
        },
        error: function (xhr, status, error) {
            console.log(error);
            $(".error").show();
            $(".error_title").show("An Error Occured");
        },
        complete: function () {
            $(".table-loader").hide();
            $(".courses-table").show();
        },
    });
}

$(document).ready(function () {
    getCourses();
});

// get courses for teacher assignment

function getCoursesTeacher() {
    $.ajax({
        url: "/dashboard/staff/get-course-data",
        type: "GET",
        success: function (response) {
            let courses = "<option selected disabled>Select course</option>";
            response.forEach(function (course, index) {
                courses += `

                <option value="${course?.id}">${course?.course_name}</option>
                `;
            });

            $("[name=course_assigned]").html(courses);
        },
        error: function (xhr, status, error) {
            console.log(error);
            $(".error").show();
            $(".error_title").show("An Error Occured");
        },
        complete: function () {},
    });
}

$(document).ready(function () {
    getCoursesTeacher();
});

// add instructors

$(document).ready(function () {
    $(".error").hide();
    $(".teacher-loading").hide();
});

function addInstructor() {
    $(".teacher-loading").show();
    $(".teacher-btn").attr("disabled", "disabled").addClass("btn-disabled");

    // Clear previous error messages
    $(".text-danger").remove(); // This removes all previous error messages

    // Create form data to handle file upload
    let formData = new FormData($(".teacher-form")[0]);
    let errorElement;

    $.ajax({
        url: "/dashboard/staff/add-instructor",
        type: "POST",
        data: formData,
        processData: false, // Important to send FormData object correctly
        contentType: false, // Important for file upload
        success: function (response) {
            if (response.status === "success") {
                // Show success message
                $(".flash").show();
                $(".notificationPara").html("Instructor added successfully!");
                // Reset the form
                $(".teacher-form")[0].reset();
            } else {
                // Show general error if any other issue occurs
                $(".error").show();
                $(".error_title").html("Error adding instructor.");
            }
        },
        error: function (xhr, status, error) {
            console.log(xhr.statusText);

            if (xhr.status === 422) {
                // Display validation errors
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    errorElement =
                        '<p class="text-danger fw-medium m-0">' +
                        value[0] +
                        "</p>";
                    $('input[name="' + key + '"]').after(errorElement);
                    $('select[name="' + key + '"]').after(errorElement); // in case of select fields
                });
            } else {
                // Show error for non-validation issues
                $(".error").show();
                $(".error_title").html("An Error Occurred");
            }
        },
        complete: function () {
            // Hide the loader and reset button states
            $(".teacher-loading").hide();
            $(".teacher-btn")
                .removeAttr("disabled")
                .removeClass("btn-disabled");

            $("#image-preview").hide();
            // Reset the form only if the submission was successful
            $(".teacher-form")[0].reset();
        },
    });
}

$(".teacher-btn").click(function (e) {
    e.preventDefault();
    addInstructor();
});

$(document).ready(function () {
    // Image preview functionality
    $('input[name="image"]').on("change", function (e) {
        let reader = new FileReader();
        reader.onload = function (e) {
            $("#image-preview").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(e.target.files[0]);
    });

    // Password show/hide functionality
    $(".toggle-password")
        .off("click")
        .on("click", function () {
            let passwordInput = $(this)
                .closest(".form-control")
                .find('input[name="password"]');
            let icon = $(this).find("i");

            console.log(
                "Before:",
                passwordInput.attr("type"),
                icon.attr("class")
            );

            if (passwordInput.attr("type") === "password") {
                passwordInput.attr("type", "text");
                icon.removeClass("bi-eye-slash").addClass("bi-eye");
                console.log("Changed to text, icon to bi-eye");
            } else {
                passwordInput.attr("type", "password");
                icon.removeClass("bi-eye").addClass("bi-eye-slash");
                console.log("Changed to password, icon to bi-eye-slash");
            }

            console.log(
                "After:",
                passwordInput.attr("type"),
                icon.attr("class")
            );
        });
});

// hide the message

$(".AllowBtn").click(function () {
    $(".flash").fadeOut();
});

// assign batched

$(document).ready(function () {
    $(".batch-loading").hide();
    $(".teacher-skeleton").hide();
    $(".batch-btn").attr("disabled", "disabled").addClass("btn-disabled");

    // Fetch courses on page load
    $.ajax({
        url: "/dashboard/staff/get-courses",
        type: "GET",
        success: function (response) {
            let courseOptions =
                "<option disabled selected>Select Course</option>";
            response.forEach(function (course) {
                courseOptions += `<option value="${course.id}">${course.course_name}</option>`;
            });
            $('select[name="course_name_batch"]').html(courseOptions);
        },
        error: function (xhr) {
            console.log(xhr.statusText);
        },
    });

    // Fetch teachers based on the selected course
    $('select[name="course_name_batch"]').change(function () {
        const course_id = $(this).val();
        // $(".batch-loading").show(); // Show overall loading
        $(".teacher-skeleton").show(); // Show teacher loading specifically
        $(".teacher-assigned").hide();
        $.ajax({
            url: "/dashboard/staff/get-teachers",
            type: "POST",
            data: {
                course_id: course_id,
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                let teacherOptions =
                    "<option disabled selected>Select Teacher</option>";
                response.forEach(function (teacher) {
                    teacherOptions += `<option value="${teacher.id}">${teacher.name}</option>`;
                });
                $('select[name="teacher_assigned"]').html(teacherOptions);
            },
            error: function (xhr) {
                console.log(xhr.statusText);
            },
            complete: function () {
                $(".batch-loading").hide(); // Hide overall loading
                $(".teacher-skeleton").hide(); // Hide teacher loading
                $(".teacher-assigned").show();

                checkFormValidity();
            },
        });
    });

    // Check if both course and teacher are selected
    $('select[name="teacher_assigned"]').change(function () {
        checkFormValidity();
    });

    function checkFormValidity() {
        const courseSelected = $('select[name="course_name_batch"]').val();
        const teacherSelected = $('select[name="teacher_assigned"]').val();
        const batchSelected = $('input[name="batch_number"]').val();
        if (courseSelected && teacherSelected && batchSelected) {
            $(".batch-btn").removeAttr("disabled").removeClass("btn-disabled");
        } else {
            $(".batch-btn")
                .attr("disabled", "disabled")
                .addClass("btn-disabled");
        }
    }

    // Add Batch form submission
    $(".batch-btn").click(function (e) {
        e.preventDefault();

        $(".batch-loading").show();
        $(".batch-btn").attr("disabled", "disabled").addClass("btn-disabled");

        let formData = new FormData($(".course-form")[0]);

        $.ajax({
            url: "/dashboard/staff/add-batch",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === "success") {
                    $(".course-form")[0].reset(); // Reset the form
                    $(".flash").show();
                    $(".notificationPara").html("Batch added successfully");
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $(`input[name="${key}"]`).after(
                            `<p class="text-danger">${value[0]}</p>`
                        );
                        $(`select[name="${key}"]`).after(
                            `<p class="text-danger">${value[0]}</p>`
                        );
                    });
                }
            },
            complete: function () {
                $(".batch-loading").hide();
                checkFormValidity(); // Recheck validity after submission
            },
        });
    });
});

$(document).ready(function () {
    loadBatches(1); // Load first page of batches on page load

    function loadBatches(page) {
        $.ajax({
            url: `/dashboard/staff/batches/view-batches/?page=${page}`,
            type: "GET",
            success: function (response) {
                // Update the table body and pagination links with the response data
                $(".batches-view").html(response.batchesHtml); // You'll need to return HTML for the batches
                $(".pagination").html(response.paginationHtml); // And pagination links
            },
            error: function (xhr) {
                console.error(xhr.statusText);
            },
        });
    }

    // Handle pagination click
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).attr("href").split("page=")[1];
        loadBatches(page);
    });
});

// update batches

$(document).on("click", ".update-btn", function () {
    let batchId = $(this).data("id"); // Get the batch ID from button's data attribute
    let formData = {
        batch_no: $('input[name="batch_no"]').val(),
        teacher: $('select[name="teacher"]').val(),
        course: $('select[name="course"]').val(),
        duration: $('input[name="duration"]').val(),
        _token: $('input[name="_token"]').val(),
    };

    $.ajax({
        url: `/dashboard/staff/update-batch/${batchId}`,
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.status === "success") {
                alert(response.message);
                loadBatches(1); // Reload the batches after update
            }
        },
        error: function (xhr) {
            console.error("Error:", xhr.statusText);
        },
    });
});

// delete batch

$(document).on("click", ".delete-btn", function () {
    if (confirm("Are you sure you want to delete this batch?")) {
        let batchId = $(this).data("id"); // Get batch ID from button's data attribute

        $.ajax({
            url: `/dashboard/staff/delete-batch/${batchId}`,
            type: "DELETE",
            data: {
                _token: $('input[name="_token"]').val(),
            },
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    loadBatches(1); // Reload the batches after deletion
                }
            },
            error: function (xhr) {
                console.error("Error:", xhr.statusText);
            },
        });
    }
});

// update modal data
$(document).ready(function () {
    // When an 'Update' button is clicked
    $(document).on("click", ".update-btn", function () {
        const batchId = $(this).data("id");

        // Fetch the batch details and populate the modal
        $.ajax({
            url: `/dashboard/staff/batches/${batchId}/edit`, // Route to fetch batch details
            type: "GET",
            success: function (response) {
                $("#batchNo").val(response.batch_no);
                $("#courseAssigned").html(response.courseOptions);
                $("#teacherAssigned").html(response.teacherOptions);
                $("#duration").val(response.duration); // Populate duration field
                $("#batchId").val(batchId);
            },
            error: function (xhr) {
                console.error("Error fetching batch details:", xhr.statusText);
            },
        });
    });

    // Fetch teachers and update duration based on the selected course in the modal
    $(document).on("change", "#courseAssigned", function () {
        const courseId = $(this).val();

        // Make AJAX call to fetch teachers and course duration
        $.ajax({
            url: `/dashboard/staff/get-teachers-and-duration`, // Update the route to also return course duration
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

                // Update the course duration field
                $("#duration").val(response.course_duration);
            },
            error: function (xhr) {
                console.error(
                    "Error fetching teachers or duration:",
                    xhr.statusText
                );
            },
        });
    });

    // Save changes when the modal form is submitted
    $("#saveBatchBtn").click(function () {
        const batchId = $("#batchId").val();
        const formData = {
            batch_no: $("#batchNo").val(),
            teacher: $("#teacherAssigned").val(),
            course_id: $("#courseAssigned").val(),
            duration: $("#duration").val(),
            _token: $('input[name="_token"]').val(),
        };

        $.ajax({
            url: `/dashboard/staff/update-batch/${batchId}`,
            type: "POST",
            data: formData,
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    $("#updateBatchModal").modal("hide");
                    loadBatches(1); // Reload the batches (adjust the page number if necessary)
                }
            },
            error: function (xhr) {
                console.error("Error updating batch:", xhr.statusText);
            },
        });
    });
});
