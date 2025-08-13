<x-student-dashboard-layout>
    <section class="col-xl-10 col-lg-9 p-3 hide-scrollbar" style='height:85vh;overflow-y:scroll'>
        <div class="max-width">
            <h1>Performance Overview</h1>
            <div class="underline"></div>
        </div>
        <div class="card p-4 mb-4 border-0 shadow-sm bg-purple text-white">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <h6 class="mb-1">Total Marks</h6>
                    <h3 id="totalObtainedMarks">0</h3>
                    <p class="text-sm mb-0">out of <span id="totalMaxMarks">0</span></p>
                </div>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <h6 class="mb-1">Percentage</h6>
                    <h3 id="totalPercentage">0%</h3>
                    <div class="progress mt-2" style="height: 8px;">
                        <div id="percentageProgress" class="progress-bar bg-white" role="progressbar" style="width: 0%">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <h6 class="mb-1">Class Position</h6>
                    <h3 id="classPosition">N/A</h3>
                    <p class="text-sm mb-0">out of <span id="totalStudents">0</span> students</p>
                </div>
            </div>
        </div>

        <div class="max-width">
            <h1>Assignments</h1>
            <div class="underline"></div>
        </div>
        @include('student.partials.table-loader', ['class' => 'marks-loader'])
        <div class="table-responsive marks-table-container text-sm">
            <table class="table table-sm table-bordered table-striped text-sm text-capitalize">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Day</td>
                        <td>Student</td>
                        <td>Time</td>
                        <td>Comments</td>
                        <td>File</td>
                        <td>Max Marks</td>
                        <td>Obt Marks</td>
                    </tr>
                </thead>
                <tbody class='marks-table'></tbody>
            </table>
        </div>

        <div class="max-width">
            <h1>Tests</h1>
            <div class="underline"></div>
        </div>
        @include('student.partials.table-loader', ['class' => 'marks-loader'])
        <div class="table-responsive marks-table-container">
            <table class="table table-sm table-bordered table-striped text-sm text-capitalize">
                <thead>
                    <tr>
                        <td>Date</td>
                        <td>Day</td>
                        <td>Student</td>
                        <td>Time</td>
                        <td>Comments</td>
                        <td>File</td>
                        <td>Max Marks</td>
                        <td>Obt Marks</td>
                    </tr>
                </thead>
                <tbody class='marks-test-table'></tbody>
            </table>
        </div>
    </section>

    <style>
    .hide-scrollbar::-webkit-scrollbar {
        width: 0 !important;
    }

    .bg-purple {
        background-color: #6f42c1;
    }

    .text-purple {
        color: #6f42c1;
    }

    .underline {
        width: 50px;
        height: 3px;
        background-color: #6f42c1;
        margin-bottom: 1rem;
    }

    .card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .progress {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .card .row>div {
            margin-bottom: 1.5rem;
        }

        .card .row>div:last-child {
            margin-bottom: 0;
        }
    }
    </style>

    <script>
    setTimeout(function() {
        if (typeof $ === 'undefined') {
            console.error('jQuery not loaded. Script execution skipped.');
            return;
        }

        $(".marks-loader").show();
        $(".marks-table-container").hide();

        (function(studentDashboard) {
            studentDashboard.getMarks = function() {
                if (
                    window.location.pathname.split("/").includes("student") &&
                    window.location.pathname.split("/").includes("marks")
                ) {
                    let user_id = window.location.pathname.split("/").pop();

                    $.ajax({
                        url: `/dashboard/student/get-marks/${user_id}`,
                        type: "GET",
                        success: function(response) {
                            let assignmentTableBody = "";
                            let testTableBody = "";
                            let totalObtained = 0;
                            let totalMax = 0;
                            let studentMarks = [];

                            if (response.length === 0) {
                                assignmentTableBody = studentDashboard.createNoDataRow(
                                    "No assignments marked yet");
                                testTableBody = studentDashboard.createNoDataRow(
                                    "No tests marked yet");
                            } else {
                                response.forEach(function(mark) {
                                    const createdAt = new Date(mark.answer
                                        ?.created_at);
                                    const rowHtml = studentDashboard.createMarkRow(
                                        mark, createdAt);
                                    if (mark?.answer?.assignment?.type ==
                                        "assignment") {
                                        assignmentTableBody += rowHtml;
                                    } else {
                                        testTableBody += rowHtml;
                                    }
                                    totalObtained += parseFloat(mark.obt_marks) ||
                                        0;
                                    totalMax += parseFloat(mark.max_marks) || 0;
                                    studentMarks.push({
                                        id: mark.student?.id,
                                        marks: parseFloat(mark.obt_marks) ||
                                            0
                                    });
                                });
                            }

                            // Calculate total marks and percentage
                            $("#totalObtainedMarks").text(totalObtained.toFixed(2));
                            $("#totalMaxMarks").text(totalMax.toFixed(2));
                            const percentage = totalMax > 0 ? ((totalObtained / totalMax) *
                                100).toFixed(2) : 0;
                            $("#totalPercentage").text(`${percentage}%`);
                            $("#percentageProgress").css('width', `${percentage}%`);

                            // Fetch class position
                            studentDashboard.getClassPosition(user_id, totalObtained);

                            $(".marks-table").html(assignmentTableBody);
                            $(".marks-test-table").html(testTableBody);
                        },
                        error: function(xhr) {
                            console.error("Error fetching marks:", xhr.statusText);
                            // $("#totalObtainedMarks").text("N/A");
                            $("#totalMaxMarks").text("N/A");
                            $("#totalPercentage").text("N/A");
                            $("#classPosition").text("N/A");
                            $("#totalStudents").text("N/A");
                        },
                        complete: function() {
                            $(".marks-loader").hide();
                            $(".marks-table-container").show();
                        },
                    });
                }
            };

            studentDashboard.getClassPosition = function(user_id, studentTotalMarks) {
                $.ajax({
                    url: `/dashboard/student/get-class-position/${user_id}`,

                    type: "GET",
                    success: function(response) {
                        if (response.success && response.students) {
                            // Sort students by total marks in descending order
                            const sortedStudents = response.students
                                .map(student => ({
                                    id: student.id,
                                    total_marks: parseFloat(student
                                        .total_obtained_marks) || 0
                                }))
                                .sort((a, b) => b.total_marks - a.total_marks);

                            // Find the current student's position
                            const position = sortedStudents.findIndex(student => student
                                .id == user_id) + 1;
                            const totalStudents = sortedStudents.length;

                            $("#classPosition").text(position || "N/A");
                            $("#totalStudents").text(totalStudents || "N/A");
                        } else {
                            $("#classPosition").text("N/A");
                            $("#totalStudents").text("N/A");
                        }
                    },
                    error: function(xhr) {
                        console.error("Error fetching class position:", xhr.statusText);
                        $("#classPosition").text("N/A");
                        $("#totalStudents").text("N/A");
                    }
                });
            };

            studentDashboard.displayFile = function(file) {
                const cleanFile = file?.trim() || "";
                const fileUrl = `/public/external_uploads/${cleanFile}`;
                const ext = cleanFile.split('.').pop().toLowerCase();

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

                return `
                        <a href="${fileUrl}" target="_blank" download class="file-link" title="Download ${ext.toUpperCase()} file">
                            <img width="30" height="30"
                                src="${fileIcons[ext] || fileIcons.default}"
                                alt="${ext} file icon"
                                onerror="this.src='${fileIcons.default}'">
                        </a>`;
            };

            studentDashboard.createMarkRow = function(mark, createdAt) {
                const day = createdAt.toLocaleDateString("en-US", {
                    weekday: "long"
                });
                const date =
                    `${createdAt.getDate()}/${createdAt.getMonth() + 1}/${createdAt.getFullYear()}`;
                const time =
                    `${createdAt.getHours()}:${String(createdAt.getMinutes()).padStart(2, '0')}`;
                const commentId = `commentModal-${mark.id}`;
                const comment = mark?.comments || 'No comment provided.';

                return `
                    <tr>
                        <td class="text-sm">${date}</td>
                        <td class="text-sm">${day}</td>
                        <td class="text-sm">${mark.student?.name || "N/A"}</td>
                        <td class="text-sm">${time}</td>
                        <td class="text-sm">
                            <button class="btn btn-sm btn-purple" data-bs-toggle="modal" data-bs-target="#${commentId}">
                                <i class="bi bi-eye"></i>
                            </button>
                            <div class="modal fade" id="${commentId}" tabindex="-1" aria-labelledby="${commentId}Label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="${commentId}Label">Comment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-break">
                                            ${comment}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm">${studentDashboard.displayFile(mark.answer?.answer_file)}</td>
                        <td class="text-sm">${mark?.max_marks || "N/A"}</td>
                        <td class="text-sm">${mark.obt_marks}</td>
                    </tr>`;
            };

            studentDashboard.createNoDataRow = function(message) {
                return `
                    <tr>
                        <td colspan="8" class="text-sm text-center">${message}</td>
                    </tr>`;
            };

            studentDashboard.getMarks();

        })(window.studentDashboard = window.studentDashboard || {});
    }, 1000);
    </script>
</x-student-dashboard-layout>
