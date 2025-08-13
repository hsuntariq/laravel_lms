<ul class="d-flex flex-column bg-white p-4 gap-2" style="height:90vh;">
    <li
        class="d-flex {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'home') !== false) ? 'active' : 'text-dark' }} rounded-3 p-3 gap-3 align-items-center">
        <img src="{{ asset('images/icons/dashboard.png') }}" alt="Dashboard" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'home') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('teacher-dashboard', ['id' => auth()->user()->id]) }}">
                Dashboard
            </a>
        </h6>
    </li>

    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'assignments') !== false) ? 'active' : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/icons/assignments.png') }}" alt="Assignments" width="24" height="24"
                class="me-2"> Assignments
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center">
                <img src="{{ asset('images/icons/upload.png') }}" alt="Upload" width="24" height="24">
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('teacher-upload-assignments', ['id' => auth()->user()->id]) }}">
                    Upload Assignment
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center">
                <img src="{{ asset('images/icons/view.png') }}" alt="View" width="24" height="24">
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('teacher-view-assignments', ['id' => auth()->user()->id]) }}">
                    View Submitted Assignments
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center">
                <img src="https://cdn-icons-png.freepik.com/256/14014/14014688.png?semt=ais_incoming" alt="View"
                    width="24" height="24">
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('teacher-view-all-assignments', ['id' => auth()->user()->id]) }}">
                    All Assignments
                </a>
            </li>
        </ul>
    </div>

    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'attendance') !== false) ? 'active' : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('images/icons/attendance.png') }}" alt="Attendance" width="24" height="24" class="me-2">
            Attendance
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center">
                <img src="{{ asset('images/icons/mark.png') }}" alt="Mark" width="24" height="24">
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('teacher-attendance', ['id' => auth()->user()->id]) }}">
                    Mark Attendance
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center">
                <img src="{{ asset('images/icons/view-attendance.png') }}" alt="View Attendance" width="24" height="24">
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('teacher-view-attendance', ['id' => auth()->user()->id]) }}">
                    View Attendance
                </a>
            </li>
        </ul>
    </div>

    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'settings') !== false) ? 'active' : 'text-dark' }}">
        <img src="{{ asset('images/icons/settings.png') }}" alt="Settings" width="24" height="24">
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(fn($segment) => strpos($segment, 'settings') !== false) ? 'text-white' : 'text-dark' }}"
                href="{{ route('teacher-settings', ['id' => auth()->user()->id]) }}">
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
