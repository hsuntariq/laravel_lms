<x-teacher-dashboard-layout>
    <x-error />
    <x-toast />
    @include('teacher.partials.header')
    <main style="height: 88vh;overflow-y:scroll; position-relative">
        <x-flash />

        <div class="row justify-content-center">

            <section class="col-xl-4 col-lg-6">
                <section class="card d-flex flex-column gap-2 rounded-3 border-0 shadow p-xl-4 p-3 my-3"
                    style="background:#FFE2E6">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="icon p-3  d-flex align-items-center rounded-circle justify-content-center"
                            style="background:#FB7390;">
                            <img width="50px" src="https://gotoassignmentexpert.com/assets/img/static/writer.png"
                                alt="assignmate image">
                        </div>
                        <div class="icon p-3  d-flex align-items-center justify-content-center">
                            <img width="50px" src="https://cdn-icons-png.flaticon.com/256/10741/10741279.png"
                                alt="assignmate image">
                        </div>
                    </div>
                    <img class="count-loading"
                        src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                        width="20px" alt="Assignmate loading">
                    <h2 class="m-0 total-assignments ">

                    </h2>
                    <p class="fw-medium fs-4 text-secondary m-0">
                        Assignments
                    </p>
                    <p style="color:#8338EB" class="m-0 fw-medium">
                        Assigned
                    </p>
                </section>
            </section>
            <section class="col-xl-4 col-lg-6">
                <section class="card d-flex flex-column gap-2 rounded-3 border-0 shadow p-xl-4 p-3 my-3"
                    style="background:#DCFCE7">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="icon p-3  d-flex align-items-center rounded-circle justify-content-center"
                            style="background:#68E17D;">
                            <img width="50px" src="https://gotoassignmentexpert.com/assets/img/static/writer.png"
                                alt="assignmate image">
                        </div>
                        <div class="icon p-3  d-flex align-items-center justify-content-center">
                            <img width="50px" src="https://cdn-icons-png.flaticon.com/256/10741/10741279.png"
                                alt="assignmate image">
                        </div>
                    </div>
                    <img class="count-loading"
                        src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                        width="20px" alt="Assignmate loading">
                    <h2 class="m-0 total-tests ">

                    </h2>
                    <p class="fw-medium fs-4 text-secondary fs-3 m-0">
                        Tests
                    </p>
                    <p style="color:#8338EB" class="m-0 fw-medium">
                        Assigned
                    </p>
                </section>
            </section>
        </div>

        <form action="{{ route('upload-assignment') }}" method="POST" style="background:#FFF4DE;"
            class="assignment-data p-4 shadow col-xl-5 col-lg-7 col-sm-9 mx-auto
        my-3 rounded-3 "
            enctype="multipart/form-data">
            @csrf
            <h2 class="text-secondary text-center">
                Add an assignment/test
            </h2>
            <div class="form-group">
                <label for="topic">Topic</label>
                <input type="text" name="topic" placeholder="Enter name of the topic..." class="form-control"
                    style="background: #F9F9F9">
                @error('topic')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" placeholder="Enter description of the topic..."
                    class="form-control" style="background: #F9F9F9">
                @error('description')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Max Marks</label>
                <input type="number" name="max_marks" placeholder="e.g. 15" class="form-control"
                    style="background: #F9F9F9">
                @error('max_marks')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="batch_no">Batch no.</label>
                <select class="form-select" name="batch_no" id="">
                    @for ($i = 0; $i < 5; $i++)
                        <option value="{{ $i + 1 }}">
                        batch {{ $i + 1 }}
                        </option>
                        @endfor
                </select>
                @error('batch_no')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Deadline</label>
                <input type="datetime-local" name="deadline" placeholder="e.g. 15" class="form-control"
                    style="background: #F9F9F9">
                @error('deadline')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select name="type" placeholder="e.g. 15" class="form-control" style="background: #F9F9F9">
                    <option disabled selected>
                        Select type
                    </option>
                    <option value="assignment">
                        Assignment
                    </option>
                    <option value="test">
                        Test
                    </option>
                </select>
                @error('type')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" name="file" placeholder="e.g. 15" class="form-control"
                    style="background: #F9F9F9">
                @error('file')
                <p class="text-danger fw-medium m-0">
                    {{ $message }}
                </p>
                @enderror
            </div>
            {{-- preview goes here --}}
            <div class="file-preview"></div>
            <button type="button" class="btn my-2 add-assignment w-100 btn-purple">
                <img class="loading"
                    src="https://discuss.wxpython.org/uploads/default/original/2X/6/6d0ec30d8b8f77ab999f765edd8866e8a97d59a3.gif"
                    width="20px" alt="Assignmate loading"><span class="loading-text">Add Task</span>
            </button>
        </form>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</x-teacher-dashboard-layout>