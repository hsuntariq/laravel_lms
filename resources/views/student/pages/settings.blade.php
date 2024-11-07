<x-layout>

    <x-header />
    <hr class="my-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-10 col-lg-7 p-3">
            <h2>Basic Information</h2>
            <hr class="mt-1 my-5">
            <section class="row">
                <div class="col-sm-6">
                    <img style="object-fit: contain;border:1px solid #8338eb;" width="200px" height="200px"
                        class="rounded-circle d-block mx-auto "
                        src="https://static.vecteezy.com/system/resources/thumbnails/005/545/335/small/user-sign-icon-person-symbol-human-avatar-isolated-on-white-backogrund-vector.jpg"
                        alt="Assign Mate user image">
                </div>
                <div class="col-sm-6 d-flex flex-column gap-2">
                    <h2>UserName</h2>
                    <p class="text-secondary fw-medium">
                        Batch#16
                    </p>
                    <p class="text-secondary">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi, unde!
                    </p>
                    @include('student.partials.update-modal')
                </div>
            </section>
        </section>
    </main>
</x-layout>