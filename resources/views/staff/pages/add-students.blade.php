<x-staff-dashboard-layout>
    <x-flash />
    <x-error />
    <form method="POST" enctype="multipart/form-data"
        class="student-form col-lg-7 col-md-10 mx-auto border-purple p-3 rounded-3 shadow">
        @csrf
        <h1 class="display-6 text-center">
            Add Student
        </h1>
        <div class="form-group">
            <label for="name">Student Name</label>
            <input placeholder="e.g. John Doe" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Student Email</label>
            <input placeholder="e.g. example@mail.com" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <div class="d-flex form-control">
                <input type="password" placeholder="e.g. ******" name="password" id="password"
                    class="outline-none pass border-0 w-100" required>
                <span class="toggle-password" style="cursor:pointer;">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label for="whatsapp">WhatsApp</label>
            <input placeholder="e.g. 123456789" name="whatsapp" id="whatsapp" class="form-control">
        </div>
        <div class="form-group">
            <label for="course_assigned">Course Assigned</label>
            <select name="course_assigned" class="form-control" id="course_assigned" required>
                <option disabled selected>Select Course</option>
                <!-- dynamically populate this with the available courses -->
            </select>
        </div>
        <div class="form-group">
            <label for="batch_assigned">Batch Assigned</label>
            <select name="batch_assigned" class="form-control" id="batch_assigned" required>
                <option disabled selected>Select Batch</option>
                <!-- dynamically populate based on the selected course -->
            </select>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control" id="gender" required>
                <option disabled selected>Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Student Image</label>
            <input type="file" name="image" id="image" class="form-control">
            <img id="image-preview" src="#" alt="Image Preview" class="img-fluid mt-2"
                style="display:none; max-width: 150px;">
        </div>
        <input type="hidden" value="student" name="role">
        <button class="btn btn-purple student-btn w-100 my-2">
            <img class="student-loading"
                src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                width="20px" alt="student loading">
            Add Student
        </button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</x-staff-dashboard-layout>
