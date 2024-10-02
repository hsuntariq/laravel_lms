<x-staff-dashboard-layout>
    <x-flash />
    <x-error />
    <form action="" class="course-form col-lg-7 col-md-10 mx-auto border-purple p-3 rounded-3 shadow">
        @csrf
        <h1 class="display-6 text-center">
            Add Course
        </h1>
        <div class="form-group">
            <label for="course_name">Course Name</label>
            <input type="text" placeholder="e.g. Full stack web development" class="form-control course-name"
                name="course_name">

        </div>
        <div class="form-group">
            <label for="course_duration">Course Duration</label>
            <input type="number" placeholder="e.g. 5" class="form-control course-duration" name="course_duration">

        </div>
        <div class="form-group">
            <label for="course_fee">Course Fee</label>
            <input type="number" placeholder="e.g. 45000" class="form-control course-fee" name="course_fee">

        </div>
        <button class="btn btn-purple course-btn w-100 my-2">
            <img class="course-loading"
                src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                width="20px" alt="Assignmate loading"> Add Course
        </button>
    </form>
</x-staff-dashboard-layout>
