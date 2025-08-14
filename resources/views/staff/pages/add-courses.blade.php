<x-staff-dashboard-layout>
    <x-flash />
    <x-error />

    <form method="POST" enctype="multipart/form-data"
        class="course-form col-xl-10  col-md-10 mx-auto p-4 rounded-3 shadow-lg bg-white">
        @csrf

        <h4 class="mb-4 fw-semibold">Basic Information</h4>

        <!-- Course Title -->
        <div class="form-section">
            <div class="mb-3">
                <label class="form-label fw-semibold">Course Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Introduction to Python">
            </div>
        </div>

        <!-- Category, Level, Language -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Course Category <span class="text-danger">*</span></label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="IT & Softwares">IT & Softwares</option>
                    <option value="Design">Design</option>
                    <option value="Marketing">Marketing</option>
                    <option value="design">Management</option>
                    <option value="Finance">Finance</option>
                    <option value="Productivity">Productivity</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Course Level <span class="text-danger">*</span></label>
                <select name="level" class="form-select" required>
                    <option value="">Select Level</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Language <span class="text-danger">*</span></label>
                <select name="language" class="form-select" required>
                    <option value="">Select Language</option>
                    <option value="english">English</option>
                    <option value="urdu">Urdu</option>
                </select>
            </div>
        </div>

        <!-- Max Students, Public/Private -->
        <div class="row g-3 mb-3">
            <div class="col-md-12">
                <label class="form-label fw-semibold">Public / Private Course <span class="text-danger">*</span></label>
                <select name="visibility" class="form-select" required>
                    <option value="">Select Visibility</option>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                </select>
            </div>
        </div>

        <!-- Short Description -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Short Description</label>
            <input type="text" name="short_description" class="form-control" placeholder="Brief summary of the course">
        </div>

        <!-- Pricing -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Price (PKR) <span class="text-danger">*</span></label>
            <input type="number" name="price" class="form-control" step="0.01" required placeholder="e.g. 5000">
        </div>

        <!-- Thumbnail -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Thumbnail</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImage(event)">
            <div class="mt-2">
                <img id="thumbnailPreview" src="" alt="Course Thumbnail Preview" class="img-thumbnail d-none"
                    style="max-height:150px;">
            </div>
        </div>

        <!-- Course Description -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Course Description</label>
            <div id="editor" style="min-height: 200px;"
                placeholder="Describe the course content, modules, and objectives"></div>
            <textarea name="description" id="description" class="d-none"></textarea>
        </div>

        <!-- Learning Outcomes & Requirements -->
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">What will students learn?</label>
                <div id="learning-list">
                    <input type="text" name="learning[]" class="form-control mb-2"
                        placeholder="e.g. Understand Python basics">
                </div>
                <button type="button" class="btn btn-outline-purple btn-sm"
                    onclick="addItem('learning-list','learning[]')">+ Add New Item</button>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Requirements</label>
                <div id="requirements-list">
                    <input type="text" name="requirements[]" class="form-control mb-2"
                        placeholder="e.g. No prior experience required">
                </div>
                <button type="button" class="btn btn-outline-purple btn-sm"
                    onclick="addItem('requirements-list','requirements[]')">+ Add New Item</button>
            </div>
        </div>

        <!-- Featured -->
        <div class="form-check form-switch mb-3">
            <input class="form-check-input feature-course" type="checkbox" name="featured" id="featured" value="1">
            <label class="form-check-label" for="featured">Check this for featured course</label>
        </div>

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-purple px-4">Next</button>
        </div>
    </form>

    <div id="course-response" class="my-3"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
    // Quill Editor
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    'size': []
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        let user_id = window.location.pathname.split('/').pop(); // Assuming user_id is in the URL
        document.querySelector('#description').value = quill.root.innerHTML;

        const form = e.target;
        const formData = new FormData(form);
        const responseDiv = document.getElementById('course-response');
        responseDiv.innerHTML = '';
        responseDiv.className = 'my-3';

        // Show loading spinner
        responseDiv.innerHTML =
            `<div class="alert alert-info"><span class="spinner-border spinner-border-sm"></span> Submitting course, please wait...</div>`;

        fetch(`/dashboard/staff/add-course-data`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    responseDiv.className = 'my-3';
                    responseDiv.innerHTML =
                        `<div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> Course added successfully!</div>`;
                    form.reset();
                    quill.setContents([]);
                    document.getElementById('thumbnailPreview').classList.add('d-none');
                } else if (data.status === 'error') {
                    let errorList = '';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArr => {
                            errorList += `<li>${errArr[0]}</li>`;
                        });
                    }
                    responseDiv.className = 'my-3';
                    responseDiv.innerHTML =
                        `<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:<ul>${errorList}</ul></div>`;
                } else {
                    responseDiv.className = 'my-3';
                    responseDiv.innerHTML =
                        `<div class="alert alert-warning"><i class="bi bi-exclamation-circle"></i> Unexpected response. Please try again.</div>`;
                }
            })
            .catch(err => {
                responseDiv.className = 'my-3';
                responseDiv.innerHTML =
                    `<div class="alert alert-danger"><i class="bi bi-x-circle-fill"></i> Network error. Please check your connection and try again.</div>`;
            });
    });

    // Image Preview
    function previewImage(event) {
        const preview = document.getElementById('thumbnailPreview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.classList.remove('d-none');
    }

    // Add Dynamic Items
    function addItem(containerId, name) {
        let container = document.getElementById(containerId);
        let input = document.createElement('input');
        input.type = 'text';
        input.name = name;
        input.className = 'form-control mb-2';
        input.placeholder = 'Enter item';
        container.appendChild(input);
    }
    </script>

    <style>
    .course-form label {
        font-size: 0.95rem;
    }

    .course-form input,
    .course-form select,
    .course-form textarea {
        border-radius: 8px;
        padding: 8px 12px;
        transition: border 0.3s ease;
    }

    .course-form input:focus,
    .course-form select:focus,
    .course-form textarea:focus {}

    .ql-editor {
        min-height: 200px;
    }

    /* Add margin-bottom to each form-section */
    .form-section {
        margin-bottom: 1.5rem;
    }
    </style>
</x-staff-dashboard-layout>
