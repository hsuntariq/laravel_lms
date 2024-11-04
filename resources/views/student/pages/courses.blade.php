<x-layout>
    <x-header />
    <hr class="my-1">
    <main class="row">
        <section class="col-xl-2 col-lg-3">
            @include('student.partials.admin-sidebar')
        </section>
        <div class="col-xl-10 col-lg-9">
            <div class="d-flex justify-content-between">
                <div class="w-50" style="height:200px">

                    {!! $pieChart->container() !!}
                </div>
                <div class="w-50" style="height:200px">

                    {!! $doughnetChart->container() !!}
                </div>

            </div>
            <div class="table-responsive">

                <table class="table text-capitalize">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td>Course name</td>
                            <td>Batch #</td>
                            <td>duration</td>
                            <td>current classes</td>
                            <td>Classes Remaining</td>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td class="p-4">{{ $i + 1 }}</td>
                                <td class="p-4">FSWD</td>
                                <td class="p-4">16</td>
                                <td class="p-4">6 months</td>
                                <td class="p-4">72</td>
                                <td class="p-4">30</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    {!! $pieChart->script() !!}
    {!! $doughnetChart->script() !!}
</x-layout>
