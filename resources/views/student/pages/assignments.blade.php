<x-student-dashboard-layout>
    <x-small-navigation />

    <section class="col-xl-10 col-lg-9 p-3">
        <section class="d-flex gap-3 flex-wrap align-items-center px-3 justify-content-between">
            <div class="d-flex">
                <div class="max-width">
                    <h1>Assignments</h1>
                    <div class="underline"></div>
                </div>
            </div>
            <ul class="d-flex flex-wrap m-0 align-items-center list-unstyled text-purple fw-semibold text-sm gap-2">
                <li class="filter-button-student btn text-sm btn-sm btn-purple" data-status="all">All</li>
                <li class="filter-button-student btn text-sm btn-sm" data-status="submitted">Submitted</li>
                <li class="filter-button-student btn text-sm btn-sm" data-status="unsubmitted">Unsubmitted</li>
            </ul>
        </section>
        <div class="table-responsive assignment-table text-sm my-4" style='height:75vh;overflow-y:scroll'>
            @include('student.partials.table-loader')
            <table class="table text-center table-sm table-striped table-bordered">
                <thead>
                    <tr>
                        <td class='text-sm white-space-nowrap'>#</td>
                        <td class='text-sm white-space-nowrap'>Topic</td>
                        <td class='text-sm white-space-nowrap'>Description</td>
                        <td class='text-sm white-space-nowrap'>Marks</td>
                        <td class='text-sm white-space-nowrap'>Uploaded</td>
                        <td class='text-sm white-space-nowrap'>Deadline</td>
                        <td class='text-sm white-space-nowrap'>Task</td>
                        <td class='text-sm white-space-nowrap'>Status</td>
                        <td class='text-sm white-space-nowrap' style="width:20%">Upload</td>
                        <td class='text-sm white-space-nowrap'>Action</td>
                    </tr>
                </thead>
                <tbody id="assignmentsTableBody">
                    <!-- Assignments will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </section>

    <!-- Description Modal -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">Assignment Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="descriptionContent">
                        <!-- Description content will be inserted here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div id="output">
        <p>Loading assignments...</p>
    </div>


    <x-jquery />

    <script>
        $(document).ready(function () {
            $.ajax({
                url: '/dashboard/student/get-assignment-details', // Adjust to your endpoint URL
                type: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + 'your-auth-token-here', // Replace with actual token
                    'Accept': 'application/json'
                },
                success: function (response) {
                    // Process the response data
                },
                error: function (xhr, status, error) {
                    let errorMessage = xhr.responseJSON?.message || 'Failed to fetch assignments';
                    console.log('AJAX Error: ' + errorMessage)
                }
            });
        });






        function displayFile(file) {
            // Clean the file path (remove any unwanted characters if needed)
            const cleanFile = file.trim();
            console.log(cleanFile); // Debug the input

            // Construct the full file URL using the correct base path
            const fileUrl = `/laravel/public/external_uploads/${cleanFile}`;
            // Get file extension (case insensitive)
            const ext = file.split('.').pop().toLowerCase();

            // Supported file icons
            const fileIcons = {
                html: "/assets/file_icons/html.png",
                png: "/assets/file_icons/png.webp",
                jpeg: "/assets/file_icons/jpeg.png",
                jpg: "/assets/file_icons/jpg.png",
                docx: "/assets/file_icons/docx.webp",
                pdf: "/assets/file_icons/pdf.png",
                xlsx: "/assets/file_icons/xlsx.png",
                pptx: "/assets/file_icons/pptx.png",
                txt: "/assets/file_icons/txt.png",
                zip: "/assets/file_icons/zip.png",
                rar: "/assets/file_icons/rar.png",
                default: "/assets/file_icons/default.png"
            };

            // Return the file link with icon
            return `
            <a href="${fileUrl}" target="_blank" download class="file-link" title="Download ${ext.toUpperCase()} file">
                <img width="30" height="30"
                     src="${fileIcons[ext] || fileIcons.default}"
                     alt="${ext} file icon"
                     onerror="this.src='${fileIcons.default}'">
            </a>`;
        }

        // Function to open description modal
        function openDescriptionModal(description, topic) {
            $('#descriptionModalLabel').text(`${topic} - Description`);
            $('#descriptionContent').html(`<p>${description}</p>`);
            $('#descriptionModal').modal('show');
        }

        // Global variable to store all fetched assignments
        let allAssignment = [];

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
        function displayAssignment(assignmentsData) {
            const userId = window.location.pathname.split("/").pop();

            const filteredAssignments = assignmentsData.assignments?.filter(
                (assignment) => {
                    const currentDate = new Date();
                    const deadlineDate = new Date(assignment?.deadline);
                    return currentDate <= deadlineDate;
                }
            );

            const assignmentsHtml = filteredAssignments
                ?.map((assignment, index) => {
                    const createdAtDate = new Date(assignment.created_at);
                    const deadlineDate = new Date(assignment.deadline);
                    const options = {
                        timeZone: "Asia/Karachi",
                        month: "2-digit",
                        day: "2-digit",
                        hour: "2-digit",
                        minute: "2-digit",
                    };
                    const formattedCreatedAt = createdAtDate.toLocaleString("en-US", options);
                    const formattedDeadline = deadlineDate.toLocaleString("en-US", options);

                    const isSubmitted = assignment.status === "submitted";

                    // Truncate description for display
                    const truncatedDescription = assignment.description.length > 30 ?
                        assignment.description.substring(0, 30) + '...' :
                        assignment.description;

                    return `
                    <tr>
                        <td class="text-sm">${index + 1}</td>
                        <td class="text-sm">${assignment.topic}</td>
                        <td class="text-sm">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span class="description-preview">${truncatedDescription}</span>
                                <button class="btn btn-outline-primary btn-sm description-btn"
                                        onclick="openDescriptionModal('${assignment.description.replace(/'/g, "\\'")}', '${assignment.topic.replace(/'/g, "\\'")}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-sm">${assignment.max_marks}</td>
                        <td class="text-sm">${formattedCreatedAt}</td>
                        <td class="text-sm">${formattedDeadline}</td>
                        <td class="text-sm">
                            <div style="position: relative; display: inline-block;">
                                ${displayFile(assignment?.file)}
                                <svg
                                style="
                                    position: absolute;
                                    bottom: 0;
                                    right: 0;
                                    transform: translate(20%, 20%);
                                    width: 16px;
                                    height: 16px;
                                    background: black;
                                    border-radius: 50%;
                                    padding: 2px;
                                    pointer-events:none;
                                "
                                xmlns="http://www.w3.org/2000/svg"
                                fill="white"
                                viewBox="0 0 16 16"
                                >
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                            </div>
                        </td>
                        ${isSubmitted
                            ? `<td colspan="5" class="text-center">
                                        <i class="bi bi-check-circle-fill text-success"></i> Submitted
                                    </td>`
                            : `
                                <td class="text-sm">pending...</td>
                                <td class="text-sm">
                                    <form class="upload-form" enctype="multipart/form-data">
                                        <input name="assignment_id" type="hidden" value="${assignment.id}">
                                        <input name="user_id" type="hidden" value="${userId}">
                                        <div class="input-group d-block input-group-sm text-center">
                                            <label style='cursor:pointer;' for='answer_file_${assignment.id}'>
                                                <img class='d-block mx-auto' src='https://cdn-icons-png.flaticon.com/256/564/564834.png' width='20px' >
                                            </label>
                                            <input name="answer_file" id='answer_file_${assignment.id}' type="file" class="form-control file-input d-none">
                                            <div class="error-message text-danger" style="display: none;"></div>
                                            <span class="file-name text-sm text-muted" style="display: none;"></span>
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
                    </tr>
                `;
                })
                .join("") || "";

            $("#assignmentsTableBody").html(assignmentsHtml);

            // Add event listener for file input changes
            $(".file-input").on("change", function () {
                const fileInput = $(this);
                const fileName = fileInput.val().split("\\").pop(); // Get the file name from the path
                const fileNameDisplay = fileInput.closest(".input-group").find(".file-name");

                if (fileName) {
                    fileNameDisplay.text(fileName).show(); // Display the file name
                    fileInput.closest("tr").find(".submit-btn").prop("disabled", false); // Enable submit button
                } else {
                    fileNameDisplay.text("").hide(); // Hide if no file is selected
                    fileInput.closest("tr").find(".submit-btn").prop("disabled", true); // Disable submit button
                }
            });
        }

        // Event listener for filter buttons
        $(document).ready(function () {
            if (
                window.location.pathname.split("/")[1] === "dashboard" &&
                window.location.pathname.split("/")[2] === "student" &&
                window.location.pathname.split("/")[3] === "assignments"
            ) {
                loadStudentAssignments();
            }

            // Filter buttons
            $(".filter-button-student").on("click", function () {
                const filterType = $(this).data("status");

                $(".filter-button-student").removeClass("btn-purple");
                $(this).addClass("btn-purple");

                const assignments = allAssignment.assignments;

                let filteredAssignments = [];

                if (filterType === "submitted") {
                    filteredAssignments = assignments.filter(a => a.status === "submitted");
                } else if (filterType === "unsubmitted") {
                    filteredAssignments = assignments.filter(a => a.status !== "submitted");
                } else {
                    filteredAssignments = assignments;
                }

                const filteredAssignmentsObj = {
                    assignments: filteredAssignments
                };

                displayAssignment(filteredAssignmentsObj);
            });
        });
    </script>

    <style>
        .bg-purple {
            background-color: #6f42c1;
        }

        .btn-purple {
            background-color: #6f42c1;
            color: white;
        }

        .btn-purple:hover {
            background-color: #5a32a3;
            color: white;
        }

        .border-purple {
            border-color: #6f42c1 !important;
        }

        .text-purple {
            color: #6f42c1;
        }

        .file-name {
            margin-top: 5px;
            word-break: break-all;
            max-width: 150px;
            margin-left: auto;
            margin-right: auto;
        }

        .description-btn {
            padding: 2px 6px;
            font-size: 12px;
            min-width: auto;
        }

        .description-preview {
            font-size: 12px;
            max-width: 100px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        #descriptionContent {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</x-student-dashboard-layout>