<x-layout>
    <div class="mt-5 loader-table">
        <div class="skeleton-table-wrapper">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @for ($i = 0; $i < 3; $i++)
                            <th scope="col">
                                <div class="skeleton-box"></div>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    <!-- Skeleton Rows -->
                    @for ($i = 0; $i < 3; $i++)
                        <tr>
                            @for ($j = 0; $j < 3; $j++)
                                <td class="p-3">
                                    <div class="skeleton-box p-4"></div>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
