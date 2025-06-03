    <x-layout>
        <x-header />

        <hr class="m-1">
        <main class="row px-2">
            <section class="col-xl-2 ps-0 col-lg-3 col-md-4 col-10 my-sidebar">
                @include('teacher.partials.teacher-sidebar')
            </section>
            <section class="col-xl-10 col-lg-9 p-3">
                {{ $slot }}
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>
    </x-layout>