<x-layout>
    <x-header />

    <hr class="m-1">
    <main class="row g-0">
        <section class="col-xxl-2 col-xl-3 col-lg-3  col-10 my-sidebar ps-0">
            @include('staff.partials.staff-sidebar')
        </section>
        <section class="col-xxl-10 col-xl-9 col-lg-9  col-12" style="height:90vh;overflow-y:scroll;">
            {{ $slot }}
        </section>
    </main>

</x-layout>
