<h6 class="my-2">Top Students</h6>
@for ($i = 0; $i < 3; $i++)
    <section class="d-flex align-items-center w-100 justify-content-between my-1">
        <section class="d-flex gap-2 align-items-center">
            {{-- user image --}}
            <img width="40px" height="40px" class="rounded-circle object-contain  border-purple"
                src="https://plus.unsplash.com/premium_photo-1683121366070-5ceb7e007a97?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
                alt="Assign mate user image">
            <div class="d-flex flex-column">
                <h6>Student Name</h6>
                <p class="text-warning">Average</p>
            </div>
        </section>
        {{-- status --}}
        <button style="background:#FDEFE6;color:orange" class="btn  fw-medium rounded-pill">
            Lagging
        </button>
    </section>
@endfor
