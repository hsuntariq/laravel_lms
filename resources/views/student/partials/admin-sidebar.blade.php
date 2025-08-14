<ul class="d-flex flex-column  px-4 py-2 gap-2" style="height:90vh;">
    <li
        class="d-flex {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'home') !== false) ? 'active' : 'text-dark' }} rounded-3 p-3 gap-3 align-items-center">
        <img src="{{ asset('images/icons/dashboard.png') }}" alt="Dashboard" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'home') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-dashboard', ['id' => auth()->user()->id]) }}">
                Dashboard
            </a>
        </h6>
    </li>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'courses') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/courses.png') }}" alt="Courses" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'courses') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-courses', ['id' => auth()->user()->id]) }}">
                Courses
            </a>
        </h6>
    </li>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'assignments') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/assignments.png') }}" alt="Assignments" width="24" height="24">
        <h6 class="m-0 flex-grow-1">
            <a class="text-decoration-none d-flex align-items-center justify-content-between {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'assignments') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-assignments', ['id' => auth()->user()->id]) }}">
                <span>Assignments</span>
                <span id="assignment-badge" class="badge bg-danger rounded-pill ms-2" style="display: none;">
                    <span id="badge-count">0</span>
                </span>
            </a>
        </h6>
    </li>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'marks') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/marks.png') }}" alt="Marks" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'marks') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-marks', ['id' => auth()->user()->id]) }}">
                Marks
            </a>
        </h6>
    </li>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'attendance') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/attendance.png') }}" alt="Attendance" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'attendance') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-attendance', ['id' => auth()->user()->id]) }}">
                Attendance
            </a>
        </h6>
    </li>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'settings') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/settings.png') }}" alt="Settings" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'settings') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('student-settings', ['id' => auth()->user()->id]) }}">
                Settings
            </a>
        </h6>
    </li>
    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'logout') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/logout.webp') }}" alt="Settings" width="24" height="24">

        <form action="/sign-out" method="POST">
            @csrf

            <button class="text-decoration-none p-0 bg-transparent border-0">
                <h6 class="m-0 text-dark">
                    Logout
                </h6>
            </button>
        </form>
    </li>
</ul>

<div class="underlay d-md-block position-fixed top-0 left-0 w-100 min-vh-100 d-flex"></div>


<x-jquery />

<script>
$(document).ready(function() {
    const userId = window.location.pathname.split("/").pop();
    let allAssignment = []; // Global variable to store all fetched assignments

    $.ajax({
        url: `/dashboard/student/assignments-get/${userId}`,
        type: "GET",
        success: function(response) {
            allAssignment = response || []; // Store assignments globally
            const filteredAssignments = allAssignment.assignments?.filter(
                (assignment) => {
                    const currentDate = new Date(); // Current date
                    const deadlineDate = new Date(assignment?.deadline); // Deadline date

                    // Include assignments where:
                    // 1. Deadline is in the future or today
                    // 2. Answer array is empty (doesn't exist or has length 0)
                    return (
                        currentDate <= deadlineDate &&
                        (!assignment?.answer || assignment.answer.length === 0)
                    );
                }
            );

            // Update badge count and show the badge if there are pending assignments
            const badgeCount = filteredAssignments?.length || 0;
            $('#badge-count').text(badgeCount);

            if (badgeCount > 0) {
                $('#assignment-badge').show(); // Show badge if count > 0
            } else {
                $('#assignment-badge').hide(); // Hide badge if count = 0
            }
        },
        error: function(xhr) {
            console.error("Error fetching assignments:", xhr.statusText);
        },
    });
});
</script>
