<x-staff-dashboard-layout>
    <x-flash />
    <x-error />

    <div class="table-responsive p-3 shadow-lg col-xl-10 mx-auto ">
        <table class="table custom-table rounded-4 text-start align-middle table-borderless" id="courses-table">
            <thead style="background:linear-gradient(90deg,#6f42c1 60%,#e2d9f3);color:#fff;" class="text-start">
                <tr class="text-start">
                    <th>Thumbnail</th>
                    <th>Course Name</th>
                    <th>Category</th>
                    <th>Level</th>
                    <th>Language</th>
                    <th>Visibility</th>
                    <th>Short Description</th>
                    <th>Price</th>
                    <th>Featured</th>
                    <th>Students</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic rows here -->
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-center" id="pagination"></ul>
        </nav>
    </div>

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModals" tabindex="-1" aria-labelledby="editCourseModalsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="edit-course-forms" class="modal-content">
                <div class="modal-header bg-purple text-white">
                    <h5 class="modal-title" id="editCourseModalsLabel"><i class="bi bi-pencil-square me-2"></i>Edit
                        Course</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body position-relative">
                    <div id="edit-modal-loading"
                        class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100 bg-white"
                        style="z-index:10;display:none;">
                        <div>
                            <span class="spinner-border text-purple"></span>
                            <div class="mt-2 text-purple fw-semibold">Loading course data...</div>
                        </div>
                    </div>
                    <input type="hidden" name="id" id="edit-course-id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit-title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" id="edit-category" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Level</label>
                            <input type="text" name="level" id="edit-level" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Language</label>
                            <input type="text" name="language" id="edit-language" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Visibility</label>
                            <select name="visibility" id="edit-visibility" class="form-select" required>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" id="edit-price" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description" id="edit-short-description"
                                class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit-description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-purple w-100">
                        <span id="edit-modal-btn-text">Save Changes</span>
                        <span id="edit-modal-btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    .custom-table th {
        font-weight: 600;
        color: #fff;
        font-size: 1rem;
        border-bottom: 2px solid #e2d9f3;
        background: none;
        white-space: nowrap;
    }

    .custom-table td {
        vertical-align: middle;
        font-size: 0.97rem;
        white-space: nowrap;
        background: #f8f9fa;
        border-bottom: 1px solid #e2d9f3;
        padding: 0.7rem 0.5rem;
    }

    .course-thumb {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 2px 8px rgba(111, 66, 193, 0.08);
        border: 2px solid #e2d9f3;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4em 0.7em;
        border-radius: 6px;
        font-weight: 500;
    }

    .table-responsive {
        border: 1px solid #eee;
        border-radius: 12px;
        background: #fff;
    }

    .action-btns a {
        font-size: 1.2rem;
        margin-right: 0.5rem;
        transition: color 0.2s;
    }

    .action-btns a:hover {
        color: #6f42c1 !important;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(90deg, #6f42c1 60%, #e2d9f3);
        color: #fff;
        border: none;
        font-weight: 600;
    }

    .pagination .page-link {
        color: #6f42c1;
        border-radius: 8px !important;
        margin: 0 2px;
        border: 1px solid #e2d9f3;
        font-weight: 500;
        transition: background 0.2s, color 0.2s;
    }

    .pagination .page-link:hover {
        background: #e2d9f3;
        color: #6f42c1;
    }

    .inline-edit-input {
        min-width: 180px;
        width: 100%;
        max-width: 350px;
        display: inline-block;
    }

    .inline-edit-select {
        min-width: 120px;
        width: 100%;
        max-width: 200px;
        display: inline-block;
    }
    </style>

    <script>
    let editingCourseId = null;

    function fetchCourses2(page = 1) {
        const tbody = document.querySelector('#courses-table tbody');
        tbody.innerHTML =
            `<tr><td colspan="11" class="text-center py-4"><span class="spinner-border text-purple"></span> Loading courses...</td></tr>`;
        fetch(`/dashboard/staff/get-courses?page=${page}`)
            .then(res => res.json())
            .then(data => {
                tbody.innerHTML = '';
                data.data.forEach(course => {
                    if (editingCourseId === course.id) {
                        // Editable row
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <img src="${course.thumbnail ? '/storage/' + course.thumbnail : 'https://via.placeholder.com/45x45.png?text=NA'}" alt="Thumbnail" class="course-thumb">
                                </td>
                                <td><input type="text" class="form-control inline-edit-input" id="edit-title" value="${course.title}"></td>
                                <td><input type="text" class="form-control inline-edit-input" id="edit-category" value="${course.category}"></td>
                                <td><input type="text" class="form-control inline-edit-input" id="edit-level" value="${course.level}"></td>
                                <td><input type="text" class="form-control inline-edit-input" id="edit-language" value="${course.language}"></td>
                                <td>
                                    <select class="form-select inline-edit-select" id="edit-visibility">
                                        <option value="public" ${course.visibility === 'public' ? 'selected' : ''}>Public</option>
                                        <option value="private" ${course.visibility === 'private' ? 'selected' : ''}>Private</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control inline-edit-input" id="edit-short-description" value="${course.short_description ?? ''}"></td>
                                <td><input type="number" class="form-control inline-edit-input" id="edit-price" value="${course.price}"></td>
                                <td>
                                    <select class="form-select inline-edit-select" id="edit-featured">
                                        <option value="1" ${course.featured ? 'selected' : ''}>Yes</option>
                                        <option value="0" ${!course.featured ? 'selected' : ''}>No</option>
                                    </select>
                                </td>
                                <td>${course.students ?? '-'}</td>
                                <td class="action-btns">
                                    <button class="btn btn-success btn-sm" onclick="saveCourse(${course.id})">Save</button>
                                    <button class="btn btn-secondary btn-sm" onclick="cancelEdit()">Cancel</button>
                                </td>
                            </tr>
                        `;
                    } else {
                        // Normal row
                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <img src="${course.thumbnail ? '/storage/' + course.thumbnail : 'https://via.placeholder.com/45x45.png?text=NA'}" alt="Thumbnail" class="course-thumb">
                                </td>
                                <td>${course.title}</td>
                                <td>${course.category}</td>
                                <td>${course.level}</td>
                                <td>${course.language}</td>
                                <td>
                                    <span class="badge ${course.visibility === 'public' ? 'bg-success' : 'bg-secondary'}">${course.visibility.charAt(0).toUpperCase() + course.visibility.slice(1)}</span>
                                </td>
                                <td>${course.short_description ?? '-'}</td>
                                <td><span class="badge bg-purple">PKR ${course.price}</span></td>
                                <td>
                                    ${course.featured ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>'}
                                </td>
                                <td>${course.students ?? '-'}</td>
                                <td class="action-btns">
                                    <a href="#" class="text-muted" onclick="editCourse(${course.id});return false;" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                    <a href="#" class="text-danger" onclick="deleteCourse(${course.id});return false;" title="Delete"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        `;
                    }
                });

                // Pagination (unchanged)
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';
                if (data.last_page > 1) {
                    pagination.innerHTML += `<li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="fetchCourses2(${data.current_page - 1});return false;">&laquo;</a>
                    </li>`;
                    for (let i = 1; i <= data.last_page; i++) {
                        pagination.innerHTML += `<li class="page-item ${i === data.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="fetchCourses2(${i});return false;">${i}</a>
                        </li>`;
                    }
                    pagination.innerHTML += `<li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="fetchCourses2(${data.current_page + 1});return false;">&raquo;</a>
                    </li>`;
                }
            });
    }

    function editCourse(id) {
        editingCourseId = id;
        fetchCourses2();
    }

    function cancelEdit() {
        editingCourseId = null;
        fetchCourses2();
    }

    function saveCourse(id) {
        const data = {
            title: document.getElementById('edit-title').value,
            category: document.getElementById('edit-category').value,
            level: document.getElementById('edit-level').value,
            language: document.getElementById('edit-language').value,
            visibility: document.getElementById('edit-visibility').value,
            short_description: document.getElementById('edit-short-description').value,
            price: document.getElementById('edit-price').value,
            featured: document.getElementById('edit-featured').value,
        };

        fetch(`/dashboard/staff/update-course/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(resp => {
                if (resp.status === 'success') {
                    editingCourseId = null;
                    fetchCourses2();
                } else {
                    alert('Error updating course.');
                }
            });
    }

    function deleteCourse(id) {
        if (!confirm('Are you sure you want to delete this course?')) return;
        fetch(`/dashboard/staff/delete-course/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    fetchCourses2();
                } else {
                    alert('Error deleting course.');
                }
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchCourses2();
    });
    </script>
</x-staff-dashboard-layout>
