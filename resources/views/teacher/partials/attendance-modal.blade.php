<!-- Modal -->
<x-toast />
<div class="modal-dialog ">
    <div class="modal fade" id="attModal" tabindex="-1" aria-labelledby="attModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="attModalLabel">Attendance for Student</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body stu-att-body">

                    @include('teacher.partials.table-loader')
                    <table class="table table-bordered table-striped table-sm text-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Topic</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody class="student-attendance-table">
                            <!-- Rows will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm att-loading-btn" data-bs-dismiss="modal">
                        <img width="20px" class='att-loading' src="{{ asset('assets/images/loading.gif') }}"
                            alt="Loading...">
                        Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
