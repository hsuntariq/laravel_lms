<x-staff-dashboard-layout>
    <x-flash />
    <x-error />
    <form action="" class="teacher-form col-lg-7 col-md-10 mx-auto border-purple p-3 rounded-3 shadow">
        @csrf
        <h1 class="display-6 text-center">
            Add Instructor
        </h1>
        <div class="form-group">
            <label for="course_name">Instructor Name</label>
            <input placeholder="e.g. Name" name="name" id="" class="form-control">
        </div>
        <div class="form-group">
            <label for="course_name">Instructor Email</label>
            <input placeholder="e.g. example@mail.com" name="email" id="" class="form-control">
        </div>
        <div class="form-group ">
            <label for="course_name">Instructor Password</label>
            <div class="d-flex form-control">

                <input type="password" placeholder="e.g. ******" name="password" id=""
                    class="outline-none pass border-0 w-100">
                <span class="toggle-password" style="cursor:pointer;">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="course_name">Instructor WhatsApp</label>
            <input placeholder="e.g. Name" name="whatsapp" id="" class="form-control">
        </div>

        <div class="form-group">
            <label for="course_assigned">Course Assigned</label>
            <select name="course_assigned" class="form-control" id=""></select>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control" id="">
                <option disabled selected> <em>Choose gender</em> </option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="course_name">Instructor Image</label>
            <input type="file" name="image" id="" class="form-control">
            <img id="image-preview" src="#" alt="Image Preview" class="img-fluid mt-2"
                style="display:none; max-width: 150px;">
        </div>
        <input type="hidden" value="teacher" name="role">
        <button class="btn btn-purple teacher-btn w-100 my-2">
            <img class="teacher-loading"
                src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                width="20px" alt="teacher loading"> Add Instructor
        </button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</x-staff-dashboard-layout>
