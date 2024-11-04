<x-error />
<x-toast />
<section class="filter my-2">
    <form action="" method="POST" class="form-control d-flex align-items-center rounded-pill gap-2 w-25">
        <div style="color:#8338EB">
            <img width="20px" height="20px" src="/assets/images/topic.png" alt="assign mate topic logo">
        </div>
        <input type="text" class="border-0 w-100 input-search" name="topic_name" style="outline-width:0"
            placeholder="Topic name">
</section>

<section class="table-responsive" style="height:80vh;overflow-y:scroll">
    @include('teacher.partials.table-loader')

    <table class="table text-capitalize table-sm table-striped table-bordered text-sm teacher-attendance-mark-table">
        <thead>
            <tr>
                <th>student name</th>
                <th>percentage</th>
                <th> present</th>
                <th>absent</th>
                <th>leave</th>
                <th>remarks</th>
            </tr>
        </thead>
        <tbody class='teacher-mark-attendace'>

        </tbody>
    </table>

    <button disabled class="btn btn-purple btn-sm btn-disabled  d-block ms-auto att-mark-btn">
        <img width="30px" class="loaderattendace-loading-gif" src="{{ asset('assets/images/loading.gif') }}"
            alt="Loading..."> Submit Attendance
    </button>
</section>
</form>
