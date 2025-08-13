<x-layout>
    <x-header />
    <hr class="m-1">

    <main class="row g-0">
        <!-- Added g-0 to remove gutters -->
        <!-- Sidebar - shows on all screens but changes width -->
        <section class="col-xxl-2 col-xl-3 col-lg-3  col-10 my-sidebar ps-0">
            @include('student.partials.admin-sidebar')
        </section>

        <!-- Main content - responsive width adjustments -->
        <section class="col-xxl-7 col-xl-6 col-lg-6  col-12" style="height:90vh;overflow-y:scroll;">
            @include('student.partials.progress')
        </section>

        <!-- Notifications - responsive behavior -->
        <section class="col-xxl-3 col-xl-3 col-lg-3  col-12 order-md-last order-lg-0">
            @include('student.partials.notifications')
        </section>
    </main>
</x-layout>
