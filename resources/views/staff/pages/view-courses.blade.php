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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</x-staff-dashboard-layout>
