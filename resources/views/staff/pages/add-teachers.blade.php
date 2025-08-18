<x-staff-dashboard-layout>
    <x-flash />
    <x-error />

    <form method="POST" enctype="multipart/form-data"
        class="instructor-form col-xl-10 col-md-10 mx-auto p-4 rounded-3 shadow-lg bg-white">
        @csrf

        <h4 class="mb-4 fw-semibold">Instructor Information</h4>

        <!-- Personal Information -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="e.g. John Doe">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required placeholder="e.g. john.doe@example.com">
            </div>
        </div>

        <!-- Password & Contact -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required
                        placeholder="Minimum 8 characters">
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                <small class="text-muted">Must be at least 8 characters long</small>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">WhatsApp Number</label>
                <input type="tel" name="whatsapp" class="form-control" placeholder="e.g. +1234567890">
            </div>
        </div>

        <!-- Course & Gender -->
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <div class="">
                    <label class="form-label fw-semibold">Assigned Courses <span class="text-danger">*</span></label>
                    <div class="custom-select-container position-relative">
                        <div class="form-control d-flex justify-content-between align-items-center" id="courses-select">
                            <span id="courses-placeholder">Select courses...</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="select-checkboxes position-absolute bg-white border shadow-sm p-2 w-100"
                            style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto;">
                            <div id="courses-checkbox-container">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Selected badges will appear here -->
                    <div id="selected-courses" class="mt-2"></div>
                    <input type="hidden" name="course_assigned" id="assignedCoursesInput">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Gender</label>
                <select name="gender" class="form-select">
                    <option value="" selected>Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
        </div>

        <!-- Profile Image -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Profile Image</label>
            <input type="file" name="image" class="form-control" accept="image/*"
                onchange="previewInstructorImage(event)">
            <div class="mt-2">
                <img id="instructorImagePreview" src="" alt="Instructor Image Preview" class="img-thumbnail d-none"
                    style="max-height:150px;">
            </div>
        </div>

        <!-- Biography -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Biography</label>
            <div id="biography-editor" style="min-height: 150px;" placeholder="Tell us about the instructor..."></div>
            <textarea name="biography" id="biography" class="d-none"></textarea>
        </div>

        <!-- Expertise -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Areas of Expertise</label>
            <div id="expertise-list">
                <input type="text" name="expertise[]" class="form-control mb-2" placeholder="e.g. Web Development">
            </div>
            <button type="button" class="btn btn-outline-purple btn-sm"
                onclick="addItem('expertise-list','expertise[]')">+ Add Expertise</button>
        </div>

        <!-- Social Media -->
        <div class="mb-4">
            <h5 class="fw-semibold mb-3">Social Media Links</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">LinkedIn</label>
                    <input type="url" name="social" class="form-control" placeholder="https://linkedin.com/in/username">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Twitter/X</label>
                    <input type="url" name="social" class="form-control" placeholder="https://twitter.com/username">
                </div>
                <div class="col-md-4">
                    <label class="form-label">GitHub</label>
                    <input type="url" name="social" class="form-control" placeholder="https://github.com/username">
                </div>
            </div>
        </div>

        <!-- Featured Instructor -->


        <input type="hidden" value="teacher" name="role">

        <!-- Submit -->
        <div class="text-end">
            <button type="submit" class="btn btn-purple px-4">
                <span class="submit-text">Add Instructor</span>
                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
        </div>
        <div id="instructor-response" class="my-3"></div>
    </form>


    <!-- Quill.js -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
    // Initialize Quill Editor for Biography
    var biographyQuill = new Quill('#biography-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Form Submission
    document.querySelector('.instructor-form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Set the biography content
        document.querySelector('#biography').value = biographyQuill.root.innerHTML;

        const form = e.target;
        const formData = new FormData(form);
        const responseDiv = document.getElementById('instructor-response');
        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = submitBtn.querySelector('.submit-text');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Show loading state
        submitText.textContent = 'Processing...';
        spinner.classList.remove('d-none');
        responseDiv.innerHTML = '';
        responseDiv.className = 'my-3';

        fetch('/dashboard/staff/add-instructor', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    responseDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i> Instructor added successfully!
                    </div>
                `;
                    form.reset();
                    biographyQuill.setContents([]);
                    document.getElementById('instructorImagePreview').classList.add('d-none');
                } else if (data.status === 'error') {
                    let errorList = '';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArr => {
                            errorList += `<li>${errArr[0]}</li>`;
                        });
                    }
                    responseDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:
                        <ul>${errorList}</ul>
                    </div>
                `;
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill"></i> An error occurred. Please try again.
                </div>
            `;
            })
            .finally(() => {
                submitText.textContent = 'Add Instructor';
                spinner.classList.add('d-none');
            });
    });

    // Toggle Password Visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });
    });

    // Image Preview
    function previewInstructorImage(event) {
        const preview = document.getElementById('instructorImagePreview');
        if (event.target.files && event.target.files[0]) {
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.classList.remove('d-none');
        }
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


    document.getElementById('courses-select').addEventListener('click', function(e) {
        e.preventDefault();
        const checkboxes = document.querySelector('.select-checkboxes');
        checkboxes.style.display = checkboxes.style.display === 'none' ? 'block' : 'none';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-container')) {
            document.querySelector('.select-checkboxes').style.display = 'none';
        }
    });


    function fetchCourses() {
        fetch('/dashboard/staff/get-course-data', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('courses-checkbox-container');
                container.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(course => {
                        const div = document.createElement('div');
                        div.className = 'form-check';

                        const input = document.createElement('input');
                        input.className = 'form-check-input';
                        input.type = 'checkbox';
                        input.name = 'course_assigned[]';
                        input.value = course.id;
                        input.id = `course-${course.id}`;
                        input.addEventListener('change', updateSelectedCourses);

                        const label = document.createElement('label');
                        label.className = 'form-check-label w-100';
                        label.htmlFor = `course-${course.id}`;
                        label.textContent =
                            `${course.course_name} (${course.course_duration} months) - PKR ${course.course_fee}`;

                        div.appendChild(input);
                        div.appendChild(label);
                        container.appendChild(div);
                    });
                } else {
                    container.innerHTML = '<div class="text-muted p-2">No courses available</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching courses:', error);
                document.getElementById('courses-checkbox-container').innerHTML =
                    '<div class="text-danger p-2">Failed to load courses</div>';
            });
    }

    // Update selected courses display
    function updateSelectedCourses() {
        const checkboxes = document.querySelectorAll('#courses-checkbox-container input:checked');
        const selectedDiv = document.getElementById('selected-courses');
        const hiddenInput = document.getElementById('assignedCoursesInput');
        const placeholder = document.getElementById('courses-placeholder');

        // Update hidden input
        hiddenInput.value = Array.from(checkboxes).map(cb => cb.value).join(',');

        // Update placeholder text
        placeholder.textContent = checkboxes.length > 0 ?
            `${checkboxes.length} course(s) selected` :
            "Select courses...";

        // Display professional pill tags
        selectedDiv.innerHTML = '';
        checkboxes.forEach(cb => {
            const courseName = cb.nextElementSibling.textContent.split(' - ')[0];
            const badge = document.createElement('span');
            badge.className =
                "badge bg-light text-dark border me-2 mb-2 px-3 py-2 d-inline-flex align-items-center";
            badge.innerHTML = `
            ${courseName}
            <button type="button" class="btn-close  ms-2" aria-label="Remove">
                <i class="bi bi-x"></i>
            </button>
        `;
            // Handle remove
            badge.querySelector('button').addEventListener('click', () => {
                cb.checked = false;
                updateSelectedCourses();
            });
            selectedDiv.appendChild(badge);
        });
    }


    // Initialize on page load
    document.addEventListener('DOMContentLoaded', fetchCourses);
    </script>

    <style>
    .custom-select-container {
        cursor: pointer;
    }

    #courses-select {
        cursor: pointer;
        appearance: none;
    }

    #courses-select option {
        padding: 8px 12px;
    }

    .select-checkboxes {
        border-radius: 0.375rem;
        margin-top: 2px;
    }

    .form-check {
        padding: 8px 12px;
    }

    .form-check:hover {
        background-color: #f8f9fa;
    }

    .form-check-input {
        margin-right: 8px;
    }

    .instructor-form label {
        font-size: 0.95rem;
    }

    .instructor-form input,
    .instructor-form select {
        border-radius: 8px;
        padding: 8px 12px;
    }

    .ql-editor {
        min-height: 150px;
        border-radius: 0 0 8px 8px;
    }

    .ql-toolbar {
        border-radius: 8px 8px 0 0;
    }

    .btn-outline-purple {
        color: #6f42c1;
        border-color: #6f42c1;
    }

    .btn-outline-purple:hover {
        color: white;
        background-color: #6f42c1;
    }

    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.5em;
    }

    #courses-select {
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 8px;
        background-color: #fff;
    }

    #selected-courses .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
    }

    .select-checkboxes .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
    }

    .select-checkboxes .form-check:hover {
        background-color: #f8f9fa;
    }

    .select-checkboxes .form-check-input {
        margin: 0;
        flex-shrink: 0;
    }

    #selected-courses .badge {
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 50px;
        background-color: #6f42c1;
        color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #selected-courses .badge .btn-close {
        font-size: 0.65rem;
        /* makes X white */
    }
    </style>
</x-staff-dashboard-layout>