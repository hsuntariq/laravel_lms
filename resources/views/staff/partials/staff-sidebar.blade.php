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
                href="{{ route('staff-dashboard', ['id' => 1]) }}">
                Dashboard
            </a>
        </h6>
    </li>
    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium  border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'courses') !== false;
            })
                ? 'active'
                : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-book me-2"></i> Courses
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-plus"></div>
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('staff-add-courses', ['id' => 16]) }}">
                    Add Course
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-eye"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('staff-view-courses', ['id' => 16]) }}">
                    View Current Courses
                </a>
            </li>
        </ul>
    </div>
    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium  border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'teachers') !== false;
            })
                ? 'active'
                : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-people me-2"></i> Teachers
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-plus"></div>
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('staff-add-teachers', ['id' => 16]) }}">
                    Add teacher
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-eye"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('staff-view-teachers', ['id' => 16]) }}">
                    View teachers
                </a>
            </li>
        </ul>
    </div>
    <div class="dropdown p-0 w-100">
        <button
            class="fw-medium  border-0 w-100 text-start p-3 rounded-3 dropdown-toggle {{ collect(request()->segments())->contains(function ($segment) {
                return strpos($segment, 'batches') !== false;
            })
                ? 'active'
                : 'text-dark bg-transparent text-dark' }}"
            type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-layers me-2"></i> Batches
        </button>
        <ul class="dropdown-menu">
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-plus"></div>
                <a class="text-decoration-none dropdown-item text-dark"
                    href="{{ route('staff-add-batches')}}">
                    Add Batch
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-eye"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('staff-view-batches') }}">
                    View Batch
                </a>
            </li>
            <li class="d-flex rounded-3 p-3 gap-3 align-items-center ">
                <div class="bi bi-gift"></div>
                <a class="text-decoration-none text-dark dropdown-item"
                    href="{{ route('staff-assign-batches', ['id' => 16]) }}">
                    Assign Batch
                </a>
            </li>
        </ul>
    </div>
</ul>