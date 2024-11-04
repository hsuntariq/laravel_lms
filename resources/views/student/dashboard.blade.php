<x-layout>
    <!-- Include header -->
    <x-header />

    <hr class="m-1">

    <main class="row">
        <section class="col-xl-2 col-lg-3">
            <!-- Include admin sidebar -->
            @include('student.partials.admin-sidebar')
        </section>
        <section class="col-xl-8 col-lg-6" style="height:90vh;overflow-y:scroll;">
            <!-- Include progress section -->
            @include('student.partials.progress')
        </section>
        <section class="col-xl-2 col-lg-3">
            <!-- Include notifications section -->
            @include('student.partials.notifications')
        </section>
    </main>
    </body>

</x-layout>
