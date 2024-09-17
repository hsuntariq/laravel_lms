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
                href="{{ route('student-dashboard', ['id' => 1]) }}">
                Dashboard
            </a>
        </h6>
    </li>
    <li
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
                href="{{ route('student-courses', ['id' => 1]) }}">
                Courses
            </a>
        </h6>
    </li>
    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'assignments') !== false;
        })
            ? 'active'
            : 'text-dark' }}">
        <i class="bi bi-card-heading"></i>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'assignments') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('student-assignments', ['batch' => 5]) }}">
                Assignments
            </a>
        </h6>
    </li>
    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'marks') !== false;
        })
            ? 'active'
            : 'text-dark' }}">
        <div class="bi bi-speedometer"></div>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'marks') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('student-marks', ['id' => 5]) }}">
                Marks
            </a>
        </h6>
    </li>
    <li
        class="d-flex rounded-3 p-3 gap-3 align-items-center {{ collect(request()->segments())->contains(function ($segment) {
            return strpos($segment, 'attendance') !== false;
        })
            ? 'active'
            : 'text-dark' }}">
        <div class="bi bi-speedometer"></div>
        <h6 class="m-0">
            <a class="text-decoration-none {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'attendance') !== false;
            })
                ? 'text-white'
                : 'text-dark' }}"
                href="{{ route('student-attendance', ['id' => 1]) }}">
                Attendance
            </a>
        </h6>
    </li>
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
                href="{{ route('student-settings', ['id' => 1]) }}">
                Settings
            </a>
        </h6>
    </li>
</ul>
