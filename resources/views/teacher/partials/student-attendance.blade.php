<section class="filter my-2">
    <form action="" method="POST" class="form-control d-flex align-items-center rounded-pill gap-2 w-25">
        <div style="color:#8338EB">
            <img width="20px" height="20px" src="https://cdn-icons-png.freepik.com/256/782/782771.png?semt=ais_hybrid"
                alt="">
        </div>
        <input type="text" class="border-0 w-100 input-search" style="outline-width:0" placeholder="Topic name">
</section>
<section class="table-responsive" style="height:80vh;overflow-y:scroll">
    <table class="table text-capitalize ">
        <thead>
            <tr>
                <th>Topic name</th>
                <th>student name</th>
                <th> present</th>
                <th>absent</th>
                <th>leave</th>
                <th>remarks</th>
            </tr>
        </thead>
        <tbody>

            @for ($i = 0; $i < 10; $i++)
                <tr>
                    <td>HTML Tables</td>
                    <td>Ali Khan</td>
                    <td>
                        <input type="radio" name="attendance" class="radio form-check">
                    </td>
                    <td>
                        <input type="radio" name="attendance" class="radio form-check">
                    </td>
                    <td>
                        <input type="radio" name="attendance" class="radio form-check">
                    </td>
                    <td>
                        <textarea name="remarks" id="" cols="30" class="form-control" rows="1"></textarea>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>

    <button class="btn btn-purple d-block ms-auto">
        Submit Attendance
    </button>
</section>
</form>
