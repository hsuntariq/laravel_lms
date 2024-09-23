<ul class="d-flex flex-column bg-white p-4 gap-2" style="height:90vh;">
    <li
        class="d-flex {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'home') !== false;
        })
            ? 'active'
            : 'text-dark' }} rounded-3 p-3 gap-3 align-items-center">
        <div class="bi bi-blockquote-left"></div>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'home') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('teacher-dashboard', ['id' => 1]) }}">
                Dashboard
            </a>
        </h6>
    </li>
    {{-- <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'courses') !== false;
        })
            ? 'active'
            : 'text-dark' }}">
        <div class="bi bi-book"></div>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'courses') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('teacher-courses', ['id' => 1]) }}">
                Courses
            </a>
        </h6>
    </li> --}}
    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium  border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'assignments') !== false;
            })
                ? 'active'
                : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-clipboard2-check me-2"></i> Assignments
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-speedometer"></div>
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('teacher-upload-assignments', []) }}">
                    Upload Assignment
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-speedometer"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('teacher-view-assignments', []) }}">
                    View submitted assignments
                </a>
            </li>
        </ul>
    </div>

    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium  border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'attendance') !== false;
            })
                ? 'active'
                : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-clipboard2-check me-2"></i> Attendance
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-speedometer"></div>
                <a class="text-decoration-none dropdown-item text-dark" href="{{ route('teacher-attendance', []) }}">
                    Mark Attendance
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-speedometer"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('teacher-view-attendance', []) }}">
                    View Attendance
                </a>
            </li>
        </ul>
    </div>



    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'settings') !== false;
        })
            ? 'active'
            : 'text-dark' }}">
        <div class="bi bi-gear"></div>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'settings') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('teacher-settings', ['id' => 1]) }}">
                Settings
            </a>
        </h6>
    </li>
</ul>
