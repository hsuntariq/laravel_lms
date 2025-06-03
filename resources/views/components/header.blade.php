<nav class="d-flex flex-wrap bg-white w-100 justify-content-between align-items-center p-3">
    <section class="logo d-flex align-items-center gap-2">
        <img width="40" src="https://www.assignmentworkhelp.com/wp-content/uploads/2018/08/icon1.png"
            alt="logo for assignmate">
        <h5 class="m-0">AssignMate</h5>
    </section>

    <h5 class="center d-none d-md-block m-0">Dashboard</h5>

    <section class="info d-flex align-items-center gap-3">
        <button id="sidebarToggle" class="btn btn-light d-lg-none d-block">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="d-flex gap-1 align-items-center">
            {!! auth()->user()->image
            ? "<img width='30px' height='30px' class='rounded-circle' src='" . asset('/storage/' .
                auth()->user()->image) . "' >"
            : "<div class='bi bi-person border rounded-circle d-flex justify-content-center align-items-center p-2'>
            </div>"
            !!}

            <h6 class='m-0 text-capitalize user-name text-purple'>
                {{ auth()->user()->name }}
            </h6>

    </section>

    <!-- Sidebar toggle button (visible on small screens) -->

</nav>
