<section class="card border-0 shadow my-3 p-xl-5 p-3 rounded-3">
    <h6 class="mb-3">Struggling & Excxelling</h6>
    <div class="d-flex gap-3 my-2">
        <!-- icon -->
        <i class="bi bi-info-circle-fill text-danger"></i>
        <p class="fw-medium">
            Bottom 3 Struggling
        </p>
    </div>
    {{-- list of students --}}

    <div class="struggling-list"></div>



    <div class="d-flex gap-3 my-2">
        <!-- icon -->
        <i class="bi bi-star-fill text-warning"></i>
        <p class="fw-medium">
            Top 3 Excxelling
        </p>
    </div>
    {{-- student details --}}
    @for ($i = 0; $i < 3; $i++)
        <section class="d-flex align-items-center w-100 justify-content-between my-1">
        <section class="d-flex gap-2 align-items-center">
            {{-- user image --}}
            <img width="40px" height="40px" class="rounded-circle object-contain  border-purple"
                src="https://plus.unsplash.com/premium_photo-1683121366070-5ceb7e007a97?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                alt="Assign mate user image">
            <div class="d-flex flex-column">
                <h6>Student Name</h6>

            </div>
        </section>
        {{-- status --}}
        <button class="btn fw-medium border-purple rounded-pill">
            View
        </button>
</section>
@endfor
</section>