<header
    class="d-flex col-11 mx-auto  shadow flex-wrap justify-content-between align-items-center align-items-md-center justify-content-between bg-white p-3 mt-4 rounded-3 gap-3">
    <!-- Logo and Title -->
    <section class="d-flex align-items-center gap-2">
        <img width="30px" src="https://www.assignmentworkhelp.com/wp-content/uploads/2018/08/icon1.png"
            alt="assignmate imagelogo for assignmate">
        <h5 class="mb-0">AssignMate</h5>
    </section>

    <!-- Status Icons -->
    <section class="d-flex align-items-center gap-3">
        <div class="d-flex tooltip1 align-items-center gap-1">
            <img src="https://cdn-icons-png.freepik.com/256/9905/9905558.png?semt=ais_hybrid" alt="assignmate image"
                width="20px">
            <h6 class="m-0 total">0</h6> <!-- Will be updated to total count -->
            <div class="tooltiptext">Total assignments </div>
        </div>
        <div class="d-flex tooltip1 align-items-center gap-1">
            <img src="https://cdn-icons-png.freepik.com/256/15190/15190698.png?semt=ais_hybrid" alt="assignmate image"
                width="20px">
            <h6 class="m-0 submitted">0</h6> <!-- Will be updated to submitted count -->
            <div class="tooltiptext">Submitted assignments</div>
        </div>
        <div class="d-flex tooltip1 align-items-center gap-1">
            <img src="https://cdn.iconscout.com/icon/premium/png-256-thumb/remaining-percentage-1-872688.png"
                alt="assignmate image" width="20px">
            <h6 class="m-0 unsubmitted">0</h6> <!-- Will be updated to unsubmitted count -->
            <div class="tooltiptext">Un submitted assignments </div>
        </div>
    </section>
    <!-- Pending Button -->
    <section>
        <a href="/dashboard/student/assignments/{{ auth()->user()->id }}"
            class="btn text-dark d-none d-sm-block btn-sm d-flex align-items-center gap-1 fw-medium w-100 w-md-auto"
            style="background:#ECE5F4;">
            <i class="bi bi-play" style="color:#9C60EF"></i>
            Pending
        </a>
    </section>
</header>

<x-small-navigation />



<!-- cards -->

<section class="card border-0 p-xl-5 p-3 shadow mt-4">

    <section class="row">
        <div class="col-xxl-4 col-lg-6 ">
            <section class="card d-flex flex-column gap-2 rounded-3 border-0 shadow p-xl-4 p-3 my-3"
                style="background:#FFF4DE">
                <section class="d-flex align-items-center justify-content-between">
                    <div class="icon p-3  d-flex align-items-center rounded-circle justify-content-center"
                        style="background:#FEB49D;">
                        <img width="50px" src="https://gotoassignmentexpert.com/assets/img/static/writer.png"
                            alt="assignmate image">
                    </div>
                    <div class="icon p-3  d-flex align-items-center justify-content-center">
                        <img width="50px" src="https://cdn-icons-png.flaticon.com/256/10741/10741279.png"
                            alt="assignmate image">
                    </div>
                </section>
                <div class="loading-count" id="loading-count">
                    <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                </div>

                <h2 class="m-0 student-lessons"></h2>
                <p class="fw-medium fs-4 text-secondary m-0">
                    Lessons
                </p>
                <p style="color:#8338EB" class="m-0 fw-medium">
                    {{-- of 73 completed --}}
                </p>
            </section>
        </div>
        <section class="col-xxl-4 col-lg-6">
            <section class="card d-flex flex-column gap-2 rounded-3 border-0 shadow p-xl-4 p-3 my-3"
                style="background:#FFE2E6">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="icon p-3  d-flex align-items-center rounded-circle justify-content-center"
                        style="background:#FB7390;">
                        <img width="50px" src="https://gotoassignmentexpert.com/assets/img/static/writer.png"
                            alt="assignmate image">
                    </div>
                    <div class="icon p-3  d-flex align-items-center justify-content-center">
                        <img width="50px" src="https://cdn-icons-png.flaticon.com/256/10741/10741279.png"
                            alt="assignmate image">
                    </div>
                </div>
                <div class="loading-count" id="loading-count">
                    <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                </div>

                <h2 class="m-0 student-assignments"></h2>
                <p class="fw-medium fs-4 text-secondary m-0">
                    Assignments
                </p>
                <p style="color:#8338EB" class="m-0 fw-medium">
                    {{-- of 24 completed --}}
                </p>
            </section>
        </section>
        <section class="col-xxl-4 col-lg-12">
            <section class="card d-flex flex-column gap-2 rounded-3 border-0 shadow p-xl-4 p-3 my-3"
                style="background:#DCFCE7">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="icon p-3  d-flex align-items-center rounded-circle justify-content-center"
                        style="background:#68E17D;">
                        <img width="50px" src="https://gotoassignmentexpert.com/assets/img/static/writer.png"
                            alt="assignmate image">
                    </div>
                    <div class="icon p-3  d-flex align-items-center justify-content-center">
                        <img width="50px" src="https://cdn-icons-png.flaticon.com/256/10741/10741279.png"
                            alt="assignmate image">
                    </div>
                </div>
                <div class="loading-count" id="loading-count">
                    <img width="40px" src="{{ asset('assets/images/loading.gif') }}" alt="Loading...">
                </div>

                <h2 class="m-0 student-tests">
                </h2>
                <p class="fw-medium fs-4 text-secondary fs-3 m-0">
                    Tests
                </p>
                <p style="color:#8338EB" class="m-0 fw-medium">
                    <!-- of 73 completed -->
                </p>
            </section>
        </section>
    </section>
</section>



<!-- courses -->


<section class="card p-xl-4 p-3 mt-3 shadow border-0 rounded-3">
    <div class="d-flex">
        <h2>My Courses</h2>
    </div>

    @include('student.partials.table-loader')
    <div class="table-responsive my-3 ">

        <table class="table table-striped table-bordered table-sm  text-capitalize">
            <thead>
                <tr>
                    <td class="text-white">#</td>
                    <td class="text-white">Course name</td>
                    <td class="text-white">Batch </td>
                    <td class="text-white">Course duration</td>
                    <td class="text-white">Status</td>
                </tr>
            </thead>
            <tbody class="student-courses">

            </tbody>
        </table>
    </div>
</section>

<x-jquery />

<script>
$(document).ready(function() {
    const userId = window.location.pathname.split("/").pop();

    $.ajax({
        url: `/dashboard/student/assignments-get/${userId}`,
        type: "GET",
        beforeSend: function() {
            $('.total').html(
                `<img src='/assets/images/loading.gif' width='20px' height='20px'>`
            ); // Total assignments
            $('.submitted').html(
                `<img src='/assets/images/loading.gif' width='20px' height='20px'>`
            ); // Total assignments
            $('.unsubmitted').html(
                `<img src='/assets/images/loading.gif' width='20px' height='20px'>`
            ); // Total assignments

        },
        success: function(response) {
            const assignments = response?.assignments || [];

            // Calculate counts
            const totalCount = assignments.length;
            const submittedCount = assignments.filter(assignment =>
                assignment?.answer && assignment.answer.length > 0
            ).length;
            const unsubmittedCount = totalCount - submittedCount;
            // Update the numbers in the UI
            $('.total').text(totalCount); // Total assignments
            $('.submitted').text(submittedCount); // Submitted
            $('.unsubmitted').text(unsubmittedCount); // Unsubmitted
        },
        error: function(xhr) {
            console.error("Error fetching assignments:", xhr.statusText);
        },
    });






});


function getStudentCourses() {
    const user_id = window.location.pathname.split("/").pop();
    $.ajax({
        url: `/dashboard/student/get-students-courses/${user_id}`,
        type: "GET",
        beforeSend: function() {
            $(".loader-table").show();
        },
        success: function(response) {
            let courses = response?.map((item, index) => {
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item?.courses[index]?.course_name}</td>
                        <td>Batch ${item?.batch_assigned}</td>
                        <td>${item?.courses[index]?.course_duration}</td>
                        <td class='d-flex align-items-center gap-3 justify-content-center' style="vertical-align: middle;text-align:center">

                                    <div class="d-flex justify-content-center mx-auto w-100 tooltip1 align-items-center gap-1">
                                        <img src="https://cdn-icons-png.freepik.com/256/9905/9905558.png?semt=ais_hybrid" alt="assignmate image"
                                            width="20px">
                                        <h6 class="m-0 total">0</h6> <!-- Will be updated to total count -->
                                        <div class="tooltiptext">Total assignments </div>
                                    </div>
                                    <div class="d-flex justify-content-center mx-auto w-100 tooltip1 align-items-center gap-1">
                                        <img src="https://cdn-icons-png.freepik.com/256/15190/15190698.png?semt=ais_hybrid" alt="assignmate image"
                                            width="20px">
                                        <h6 class="m-0 submitted">0</h6> <!-- Will be updated to submitted count -->
                                        <div class="tooltiptext">Submitted assignments</div>
                                    </div>
                                    <div class="d-flex justify-content-center mx-auto w-100 tooltip1 align-items-center gap-1">
                                        <img src="https://cdn.iconscout.com/icon/premium/png-256-thumb/remaining-percentage-1-872688.png"
                                            alt="assignmate image" width="20px">
                                        <h6 class="m-0 unsubmitted">0</h6> <!-- Will be updated to unsubmitted count -->
                                        <div class="tooltiptext">Un submitted assignments </div>
                                    </div>
                    </td>
                    </tr>
                `;
            });
            $(".student-courses").html(courses);
            $(".loader-table").hide();
        },
        error: function(xhr) {
            showErrorMessages(xhr.responseJSON.errors);
        },
    });
}

getStudentCourses()
</script>
