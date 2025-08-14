<style>
:root {
    --purple: #6f42c1;
    --light-purple: #e2d9f3;
    --gray: #f8f9fa;
}

body {
    background-color: #f5f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.dashboard-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 20px;
}

.form-header {
    color: var(--purple);
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
    font-size: 1.8rem;
}

.form-container {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--light-purple);
    box-shadow: 0 6px 15px rgba(111, 66, 193, 0.1);
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 1px solid #ced4da;
    border-radius: 8px;
    padding: 10px 15px;
    height: 46px;
    transition: all 0.3s;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
}

.days-container {
    background-color: var(--gray);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 1.5rem;
    border: 1px solid #e9ecef;
}

.day-checkbox {
    display: inline-block;
    margin-right: 15px;
    margin-bottom: 10px;
}

.day-checkbox input[type="checkbox"] {
    display: none;
}

.day-checkbox label {
    display: inline-block;
    padding: 8px 15px;
    background-color: white;
    border: 1px solid #ced4da;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    font-weight: 500;
}

.day-checkbox input[type="checkbox"]:checked+label {
    background-color: var(--purple);
    color: white;
    border-color: var(--purple);
    border-radius: 6px;
}

.btn-purple {
    background-color: var(--purple);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-purple:hover {
    background-color: #5a32a3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(111, 66, 193, 0.3);
}

.btn-outline-purple {
    background-color: white;
    color: var(--purple);
    border: 1px solid var(--purple);
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-outline-purple:hover {
    background-color: var(--light-purple);
}

.time-select {
    background-color: var(--gray);
    border-radius: 8px;
    padding: 10px 15px;
    display: flex;
    align-items: center;
}

.time-select select {
    border: none;
    background: transparent;
    flex: 1;
    outline: none;
    padding: 0 5px;
}

.time-select i {
    color: #6c757d;
    margin-right: 10px;
}

.form-section {
    margin-bottom: 1.5rem;
}

.form-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, var(--light-purple), transparent);
    margin: 1.5rem 0;
}

.max-students {
    max-width: 150px;
}
</style>
<x-staff-dashboard-layout>
    <x-flash />
    <x-error />
    <form action="" class="course-form col-xl-10 bg-white col-md-10 mx-auto  p-3 rounded-3 shadow">
        @csrf
        <h6 class="form-header my-4 text-start fs-5"> <i class="bi bi-calendar"></i> Create New Batch Assignment</h6>
        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Batch Number</label>
                    <input type="text" class="form-control" placeholder="e.g., C5101-8001">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select class="form-select" id="course-select">
                        <option selected disabled>Select a course</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Teacher</label>
                    <select class="form-select" id="teacher-select">
                        <option selected disabled>Select a teacher</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <select class="form-select">
                        <option selected disabled>Select a branch</option>
                        <option>Main Campus</option>
                        <option>Downtown Center</option>
                        <option>Westside Annex</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <label class="form-label">Class Days</label>
            <div class="days-container">
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="monday" name="days" value="Monday">
                    <label for="monday">Monday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="tuesday" name="days" value="Tuesday">
                    <label for="tuesday">Tuesday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="wednesday" name="days" value="Wednesday">
                    <label for="wednesday">Wednesday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="thursday" name="days" value="Thursday">
                    <label for="thursday">Thursday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="friday" name="days" value="Friday">
                    <label for="friday">Friday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="saturday" name="days" value="Saturday">
                    <label for="saturday">Saturday</label>
                </div>
                <div class="day-checkbox">
                    <input type="checkbox" class="btn" id="sunday" name="days" value="Sunday">
                    <label for="sunday">Sunday</label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div id="class-links-container"></div>
        </div>

        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Start Date</label>
                    <input type="text" class="form-control" placeholder="mm/dd/yyyy">
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="text" class="form-control" placeholder="mm/dd/yyyy">
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Start Time</label>
                    <div class="time-select">
                        <i class="fas fa-clock"></i>
                        <select>
                            <option selected disabled>---</option>
                            <option>8:00 AM</option>
                            <option>9:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>12:00 PM</option>
                            <option>1:00 PM</option>
                            <option>2:00 PM</option>
                            <option>3:00 PM</option>
                            <option>4:00 PM</option>
                            <option>5:00 PM</option>
                            <option>6:00 PM</option>
                            <option>7:00 PM</option>
                            <option>8:00 PM</option>
                            <option>9:00 PM</option>
                            <option>10:00 PM</option>
                            <option>11:00 PM</option>
                            <option>12:00 PM</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Time</label>
                    <div class="time-select">
                        <i class="fas fa-clock"></i>
                        <select>
                            <option selected disabled>---</option>
                            <option>8:00 AM</option>
                            <option>9:00 AM</option>
                            <option>10:00 AM</option>
                            <option>11:00 AM</option>
                            <option>12:00 PM</option>
                            <option>1:00 PM</option>
                            <option>2:00 PM</option>
                            <option>3:00 PM</option>
                            <option>4:00 PM</option>
                            <option>5:00 PM</option>
                            <option>6:00 PM</option>
                            <option>7:00 PM</option>
                            <option>8:00 PM</option>
                            <option>9:00 PM</option>
                            <option>10:00 PM</option>
                            <option>11:00 PM</option>
                            <option>12:00 PM</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>



        <div class="form-divider"></div>

        <div class="row g-3">
            <div class="col-md-6">
                <button type="button" id="create-batch-btn" class="btn btn-purple w-100">Create Batch
                    Assignment</button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-outline-purple w-100">Cancel</button>
            </div>
        </div>
        <div id="batch-feedback" class="mt-3"></div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
    // AJAX for creating batch
    document.getElementById('create-batch-btn').addEventListener('click', function() {
        const feedback = document.getElementById('batch-feedback');
        feedback.innerHTML = '';
        const form = document.querySelector('form.course-form');
        const formData = new FormData();

        // Collect values
        formData.append('batch_number', form.querySelector('input[placeholder="e.g., C5101-8001"]').value);
        formData.append('course_name_batch', form.querySelector('#course-select').value);
        formData.append('teacher_assigned', form.querySelector('#teacher-select').value);
        formData.append('branch', form.querySelector(
            'select.form-select:not(#course-select):not(#teacher-select)').value);

        // Days
        const days = Array.from(form.querySelectorAll('input[name="days"]:checked')).map(cb => cb.value);
        days.forEach(day => formData.append('days[]', day));

        // Class links
        days.forEach(day => {
            const input = form.querySelector('input[name="class_links[' + day + ']"]');
            if (input) {
                formData.append('class_links[' + day + ']', input.value);
            }
        });

        // Dates
        formData.append('start_date', form.querySelector('input[placeholder="mm/dd/yyyy"]').value);
        formData.append('end_date', form.querySelectorAll('input[placeholder="mm/dd/yyyy"]')[1].value);

        // Times
        formData.append('start_time', form.querySelectorAll('.time-select select')[0].value);
        formData.append('end_time', form.querySelectorAll('.time-select select')[1].value);



        // CSRF
        formData.append('_token', form.querySelector('input[name="_token"]').value);

        fetch('/dashboard/staff/add-batch', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    feedback.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    form.reset();
                    updateClassLinks();
                } else {
                    let errorMsg = '';
                    if (data.errors) {
                        Object.values(data.errors).forEach(errArr => {
                            if (Array.isArray(errArr)) {
                                errArr.forEach(e => errorMsg += '<div>' + e + '</div>');
                            } else {
                                errorMsg += '<div>' + errArr + '</div>';
                            }
                        });
                    } else if (data.message) {
                        errorMsg = data.message;
                    }
                    feedback.innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
                }
            })
            .catch(() => {
                feedback.innerHTML =
                    '<div class="alert alert-danger">An error occurred. Please try again.</div>';
            });
    });
    // Fetch courses on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/dashboard/staff/get-course-data')
            .then(response => response.json())
            .then(courses => {
                const courseSelect = document.getElementById('course-select');
                courseSelect.innerHTML = '<option selected disabled>Select a course</option>';
                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
            });
    });

    // Fetch teachers when a course is selected
    document.getElementById('course-select').addEventListener('change', function() {
        const courseId = this.value;
        fetch('/dashboard/staff/get-teachers-and-duration', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({
                    course_id: courseId
                })
            })
            .then(response => response.json())
            .then(teachers => {
                const teacherSelect = document.getElementById('teacher-select');
                teacherSelect.innerHTML = '<option selected disabled>Select a teacher</option>';
                teachers?.teachers?.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.textContent = teacher.name;
                    teacherSelect.appendChild(option);
                });
            });
    });
    // Add interactivity to day checkboxes
    document.querySelectorAll('.day-checkbox input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                this.parentElement.classList.add('active');
            } else {
                this.parentElement.classList.remove('active');
            }
            updateClassLinks();
        });
    });

    function updateClassLinks() {
        const selectedDays = Array.from(document.querySelectorAll('.day-checkbox input[type="checkbox"]:checked')).map(
            cb => cb.value);
        const container = document.getElementById('class-links-container');
        container.innerHTML = '';
        selectedDays.forEach(day => {
            const div = document.createElement('div');
            div.className = 'mb-3';
            const label = document.createElement('label');
            label.className = 'form-label';
            label.textContent = day + ' Class Link';
            label.setAttribute('for', 'class-link-' + day.toLowerCase());
            const input = document.createElement('input');
            input.type = 'url';
            input.className = 'form-control';
            input.name = 'class_links[' + day + ']';
            input.id = 'class-link-' + day.toLowerCase();
            input.placeholder = 'Enter ' + day + ' class link';
            div.appendChild(label);
            div.appendChild(input);
            container.appendChild(div);
        });
    }

    // Initial call in case some days are pre-selected
    updateClassLinks();

    // Add date picker functionality (would be implemented with a date picker library in a real application)
    document.querySelectorAll('input[placeholder="mm/dd/yyyy"]').forEach(input => {
        input.addEventListener('focus', function() {
            this.type = 'date';
        });

        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.type = 'text';
            }
        });
    });
    </script>
</x-staff-dashboard-layout>
