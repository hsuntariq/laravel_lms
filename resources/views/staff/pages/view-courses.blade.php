<x-staff-dashboard-layout>
    @include('student.partials.table-loader')
    <div class="table-responsive courses-table" style="height:85vh;overflow-y:scroll">
        <table class="table text-capitalize">
            <thead>
                <tr>

                    <td>id</td>
                    <td>name</td>
                    <td>duration</td>
                    <td>fee</td>
                    <td>delete</td>
                    <td>update</td>
                </tr>
            </thead>
            <tbody class="courses">

            </tbody>
        </table>
    </div>
</x-staff-dashboard-layout>
