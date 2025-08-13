<!-- All Students Modal -->
<div class="modal fade" id="allStudentsModal" tabindex="-1" aria-labelledby="allStudentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="allStudentsModalLabel">All Students</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="all-students-list">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/loading.gif') }}" width="40px" alt="Loading...">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="card p-xl-5 rounded-3 p-3 border-0 shadow">
    <div class="d-flex justify-content-between">
        <h6>Class Statistics</h6>
        <p class="fw-medium text-secondary text-sm">
            {{ now()->format('F Y') }}
        </p>
    </div>
    <div class="d-flex justify-content-between align-items-center text-center gap-4">
        <div class="d-flex flex-column" data-bs-toggle="modal" data-bs-target="#allStudentsModal"
            data-batch="{{ request()->input('batch_no') }}" data-course="{{ request()->input('course_no') }}"
            id="total-students-trigger">
            <img width="100px" height="100px"
                src="https://static.vecteezy.com/system/resources/thumbnails/008/845/859/small/cute-boy-happy-jump-png.png"
                alt="Assign Mate total students logo">
            <div class="d-flex flex-column">
                <div class="custom-loader am-loading-strength" id="am-loading-strength">
                    <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                </div>
                <p class="text-purple text-sm am-placeholder-text"></p>
                <div class="am-total-strength">
                    <h6 class="am-total-students"></h6>
                    <p class="fw-medium text-secondary text-sm">Total</p>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="d-flex flex-column">
                {{-- icon --}}
                <img width="100px" height="100px"
                    src="https://www.shutterstock.com/image-illustration/3d-student-character-flying-on-260nw-2158695513.jpg"
                    alt="Assign Mate total students logo">
                {{-- text --}}
                <div class="d-flex flex-column">
                    <div class="custom-loader am-loading-strength" id="am-loading-strength">
                        <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                    </div>
                    <p class="text-purple text-sm am-placeholder-text">
                    </p>
                    <div class="am-total-strength">
                        <h6 class="am-excelling-students"></h6>
                        <p class="fw-medium text-secondary text-sm">
                            Excelling
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column">
            <div class="d-flex flex-column">
                {{-- icon --}}
                <img width="100px" height="100px"
                    src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQN-sR0ohGPm_A3FQDmBa4RBsEjuksKKJS5E049zyuVBQmrpwje"
                    alt="Assign Mate total students logo">
                {{-- text --}}
                <div class="d-flex flex-column">
                    <div class="custom-loader am-loading-strength" id="am-loading-strength">
                        <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                    </div>
                    <p class="text-purple text-sm am-placeholder-text">
                    </p>
                    <div class="am-total-strength">
                        <h6 class="am-struggling-students"></h6>
                        <p class="fw-medium text-secondary text-sm">
                            Struggling
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="classes-count my-2">
        <div class="card border-0">
            <div class="image-attenadnce align-items-center gap-3 d-flex p-xl-5 p-3 rounded-3">
                <img width="60px"
                    src="https://ps.w.org/wp-employee-attendance-system/assets/icon-256x256.png?rev=2414339"
                    alt="Assign Mate attendace image">
                <div class="d-flex w-100 flex-column">
                    <p class="text-sm text-secondary">
                        Progress
                    </p>
                    <div class="progress" style="height: 5px">
                        <div class="progress-bar w-75 bg-purple">
                        </div>
                    </div>
                    <p class="text-sm fw-bold">
                        30% of the classes
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Detail Modal -->
<div class="modal fade" id="studentDetailModal" tabindex="-1" aria-labelledby="studentDetailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="studentDetailModalLabel">Student Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="modalStudentImage" src="" class="rounded-circle border border-3 border-purple"
                        style='object-fit:contain;' width="120" height="120" alt="Student Image">
                    <h4 class="mt-3" id="modalStudentName">Student Name</h4>
                    <div class="badge bg-purple text-white" id="modalStudentEmail">email@example.com</div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Performance</h6>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-3" style="height: 10px;">
                                        <div id="modalStudentProgress" class="progress-bar bg-purple"
                                            role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">Marks</h6>
                                <div class="d-flex justify-content-between">
                                    <span>Obtained:</span>
                                    <strong id="modalObtainedMarks">0</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Total:</span>
                                    <strong id="modalTotalMarks">0</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<x-jquery />
<script>
    function amGetStudentProgressData(batch_no, course_no) {
        let user_id = window.location.pathname.split("/").pop();
        $.ajax({
            url: `/dashboard/teacher/get-relevent-students-info/${user_id}`,
            type: "POST",
            data: {
                batch_no,
                course_no,
                _token: '{{ csrf_token() }}' // Add CSRF token
            },
            beforeSend: function () {
                $(".am-total-strength").hide();
                $(".am-excelling-strength").hide();
                $(".am-struggling-strength").hide();
                $("#am-doughnutChartCanvas").hide();
                $("#am-doughnutChartCanvas2").hide();

                $(".am-loading-strength").show();
                $(".am-loading-chart").show();
            },
            success: function (response) {
                $(".am-total-strength").show();
                $(".am-total-students").html(response.students);
                $(".am-excelling-students").html(response?.excelling_students?.length || 0);
                $(".am-struggling-students").html(response?.struggling_students?.length || 0);
                $(".am-loading-strength").hide();
                $(".am-loading-chart").hide();
                $("#am-doughnutChartCanvas").show();
                $("#am-doughnutChartCanvas2").show();

                amGetStudentsPerformance(response?.struggling_students, ".am-struggling-list");
                amGetStudentsPerformance(response?.excelling_students, ".am-excelling-list");
                amGetStudentsPerformance(response?.average_students, ".am-average-list");
                amGetStudentsPercentage(response.students, response?.struggling_students,
                    ".am-struggling-percentage");
                amGetStudentsPercentage(response.students, response?.excelling_students,
                    ".am-excelling-percentage");
                amGetStudentsPercentage(response.students, response?.average_students,
                    ".am-average-percentage");
            },
            error: function (xhr) {
                $(".am-loading-strength").hide();
                $("#am-doughnutChartCanvas").show();
                $(".am-loading-chart").hide();
            },
            complete: function () {
                $(".am-loading-strength").hide();
                $(".am-loading-chart").hide();
                $("#am-doughnutChartCanvas").show();
            },
        });
    }

    function amGetStudentsPercentage(total, response, input) {
        let percentage = ((response?.length / total) * 100).toFixed(2) || 0;
        $(input).html(`${percentage}%`);
    }

    function amGetStudentsPerformance(response, input) {
        let student_list = response?.map((item, index) => {
            const marks = ((item?.total_obtained_marks / item?.total_max_marks) * 100).toFixed(2) || 0;
            const imagePath = item?.image ?
                `/laravel/public/external_uploads/${item.image}` :
                "/assets/images/user-image.png";

            const studentData = JSON.stringify(item)
                .replace(/"/g, '"')
                .replace(/'/g, "");

            return `
            <section class="d-flex text-capitalize align-items-center w-100 justify-content-between my-1">
                <section class="d-flex gap-2 justify-content-between align-items-center mb-2">
                    <img width="40px" height="40px" class="rounded-circle object-contain border-purple"
                        src="${imagePath}"
                        alt="Student image">
                    <div class="d-flex flex-column">
                        <h6>${item.name || "Student Name"}</h6>
                        <p class='fw-semibold'>Marks Percentage:
                            <span class='${marks > 40 ? "text-success" : "text-danger"}'>
                                ${marks}%
                            </span>
                        </p>
                    </div>
                </section>
                <button type="button" class="btn fw-medium border-purple rounded-pill view-student-btn"
                        data-student='${studentData}'>
                    View
                </button>
            </section>
        `;
        }).join("") || "<p>No students found</p>";

        $(input).html(student_list);
    }

    function showStudentModal(student) {
        const percentage = ((student.total_obtained_marks / student.total_max_marks) * 100).toFixed(2) || 0;
        let imagePath = student.image ?
            student.image.includes('User_images') ?
                `/laravel/public/external_uploads/${student.image}` :
                student.image :
            "/assets/images/user-image.png";

        $('#modalStudentImage').attr('src', imagePath);
        $('#modalStudentName').text(student.name || "Student Name");
        $('#modalStudentEmail').text(student.email || "No email provided");
        $('#modalObtainedMarks').text(student.total_obtained_marks.toFixed(2) || 0);
        $('#modalTotalMarks').text(student.total_max_marks || 0);
        $('#modalStudentPercentage').text(`${percentage}%`);
        $('#modalStudentProgress').css('width', `${percentage}%`);

        const modal = new bootstrap.Modal(document.getElementById('studentDetailModal'));
        modal.show();
    }

    function amGetAllStudents(batch_no, course_no) {
        let user_id = window.location.pathname.split("/").pop();

        $.ajax({
            url: `/dashboard/teacher/get-all-students/${user_id}`,
            type: "POST",
            data: {
                batch_no,
                course_no,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function () {
                $('.all-students-list').html(`
                <div class="text-center">
                    <img src="{{ asset('assets/images/loading.gif') }}" width="40px" alt="Loading...">
                </div>
            `);
            },
            success: function (response) {
                console.log(response)
                if (response.success) {
                    let student_list = response.students.map((item, index) => {
                        const imagePath = item.image ?
                            `/laravel/public/external_uploads/${item.image}` :
                            "/assets/images/user-image.png";

                        const studentData = JSON.stringify(item)
                            .replace(/"/g, '"')
                            .replace(/'/g, "");

                        return `
                        <section class="d-flex text-capitalize align-items-center w-100 justify-content-between my-2 border-bottom pb-2">
                            <section class="d-flex gap-2 align-items-center">
                                <img width="40px" height="40px" class="rounded-circle object-contain border-purple"
                                    src="${imagePath}"
                                    alt="Student image">
                                <div class="d-flex flex-column">
                                    <h6>${item.name || "Student Name"}</h6>
                                    <h6 class='bg-purple text-white px-2 ps-0 py-1 rounded-3 my-1' style='font-size:0.9rem;width:max-content;'>${item.email || "Student Email"}</h6>
                                    <p class='fw-semibold'>Marks Percentage:
                                        <span class='${item.percentage > 40 ? "text-success" : "text-danger"}'>
                                            ${item.percentage}%
                                        </span>
                                    </p>
                                </div>
                            </section>

                        </section>
                    `;
                    }).join("") || "<p>No students found</p>";

                    $('.all-students-list').html(student_list);
                } else {
                    $('.all-students-list').html("<p>Error loading students</p>");
                }
            },
            error: function (xhr) {
                $('.all-students-list').html("<p>Error loading students</p>");
            }
        });
    }


    function amMarkOverdueAssignments(batch_no, course_no) {
        $.ajax({
            url: `/dashboard/teacher/mark-overdue-assignments`,
            type: "POST",
            data: {
                batch_no,
                course_no,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    // After marking, refresh the student list
                    amGetAllStudents(batch_no, course_no);
                    // Optionally notify the user
                } else {
                    $('.all-students-list').html(`<p>${response.message}</p>`);
                }
            },
            error: function (xhr) {
                $('.all-students-list').html("<p>Error marking overdue assignments</p>");
            }
        });
    }

    $(document).ready(function () {
        if (window.location.pathname.split("/").includes("teacher")) {
            amGetInfoStudents();
        }

        setTimeout(() => {
            $(document).on('click', '.view-student-btn', function (e) {
                console.log('clicked')
                e.preventDefault();
                try {
                    const rawData = $(this).attr('data-student');
                    const decodedData = rawData
                        .replace(/"/g, '"')
                        .replace(/'/g, "'");

                    const student = JSON.parse(decodedData);
                    showStudentModal(student);
                } catch (e) {
                    console.error("Error parsing student data:", e);
                    const btn = $(this);
                    const studentName = btn.siblings().find('h6').text();
                    $('#modalStudentName').text(studentName || "Student");
                    $('#modalStudentEmail').text("Data unavailable");
                    const modal = new bootstrap.Modal(document.getElementById(
                        'studentDetailModal'));
                    modal.show();
                }
            });

            $('#total-students-trigger').on('click', function () {
                let batch_no = $('select[name="batch_no"]').val() || $(
                    'select[name="batch_no"] option:first').val();
                let course_no = $('select[name="course_name_teacher"]').val() || $(
                    'select[name="course_name_teacher"] option:first').val();

                if (batch_no && course_no) {
                    amGetAllStudents(batch_no, course_no);
                    amMarkOverdueAssignments(batch_no, course_no);

                } else {
                    $('.all-students-list').html("<p>Please select a course and batch</p>");
                }
            });
        }, 500);
    });

    function amGetInfoStudents() {
        setTimeout(() => {
            let batch_no = $('select[name="batch_no"]').val() || $('select[name="batch_no"]')
                .find("option:eq(1)")
                .val();
            let course_no = $('select[name="course_name_teacher"]').val() || $("select[name='course_name_teacher']")
                .find("option:eq(1)")
                .val();
            if (batch_no && course_no) {
                amGetStudentProgressData(batch_no, course_no);
            }
        }, 1000);

        $('select[name="batch_no"]').on("change", function () {
            $(".am-loading-strength").show();
            $(".am-placeholder-text").hide();
            let batch_no = $(this).val() || $('select[name="batch_no"] option:first').val();
            let course_no = $('select[name="course_name_teacher"]').val() || $(
                'select[name="course_name_teacher"] option:first').val();
            if (batch_no && course_no) {
                amGetStudentProgressData(batch_no, course_no);
            }
        });

        $('select[name="course_name_teacher"]').on("change", function () {
            $(".am-loading-strength").show();
            $(".am-placeholder-text").hide();
            let batch_no = $('select[name="batch_no"]').val() || $('select[name="batch_no"] option:first')
                .val();
            let course_no = $(this).val() || $('select[name="course_name_teacher"] option:first').val();
            if (batch_no && course_no) {
                amGetStudentProgressData(batch_no, course_no);
            }
        });
    }
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

    #studentDetailModal .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    #studentDetailModal .modal-header {
        border-bottom: none;
    }

    #studentDetailModal .progress {
        border-radius: 5px;
        background-color: #f0f0f0;
    }
</style>