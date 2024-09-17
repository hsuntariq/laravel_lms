<x-staff-dashboard-layout>
    <x-flash />
    <x-error />
    <form action="" class="course-form col-lg-7 col-md-10 mx-auto border-purple p-3 rounded-3 shadow">
        @csrf
        <h1 class="display-6 text-center">
            Add Batch
        </h1>
        <div class="form-group">
            <label for="course_name">Course Name</label>
            <select name="course_name_batch" id="" class="form-control"></select>
        </div>
        <div class="form-group">
            <label for="course_duration">Batch number</label>
            <input type="number" placeholder="e.g. 5" class="form-control batch_number" name="batch_number">

        </div>
        <div class="form-group">
            <label for="course_fee">Teacher</label>
            <select name="teacher_assigned" id="" class="form-control"></select>
        </div>
        <button class="btn btn-purple batch-btn w-100 my-2">
            <img class="batch-loading"
                src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                width="20px" alt="Assignmate loading"> Add Batch
        </button>
    </form>
</x-staff-dashboard-layout>
