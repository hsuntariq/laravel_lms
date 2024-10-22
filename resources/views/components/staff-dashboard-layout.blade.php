    <x-layout>
        <x-header />

        <hr class="m-1">
        <main class="row">
            <section class="col-xl-2 col-lg-3">
                @include('staff.partials.staff-sidebar')
            </section>
            <section class="col-xl-10 col-lg-9 p-3">
                {{ $slot }}
            </section>
        </main>

    </x-layout>
