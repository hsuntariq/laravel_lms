<div class="d-flex justify-content-between flex-wrap align-items-center">
    <h5 class="text-capitalize">
        {{ request()->segment(3) }}
    </h5>
    <form action="" class="d-flex gap-3 my-2">
        <select name="course_name_teacher" id="course_name_teacher"
            class="form-select form-select-sm border-purple text-purple fw-medium bg-transparent">
        </select>
        <select name="batch_no" id="batch_no"
            class="form-select form-select-sm border-purple text-purple fw-medium bg-transparent">
        </select>
    </form>
    <script>
    // Fetch courses and batches for teacher
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/dashboard/teacher/get-relevent-batches')
            .then(response => response.json())
            .then(data => {
                const courseSelect = document.getElementById('course_name_teacher');
                const batchSelect = document.getElementById('batch_no');
                courseSelect.innerHTML = '<option selected disabled>Select a course</option>';
                batchSelect.innerHTML = '<option selected disabled>Select a batch</option>';
                data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    courseSelect.appendChild(option);
                });
                data.batches.forEach(batch => {
                    const option = document.createElement('option');
                    option.value = batch.batch_no;
                    option.textContent = batch.batch_no;
                    batchSelect.appendChild(option);
                });
            });
    });

    // Listen for changes and trigger calendar update
    document.getElementById('course_name_teacher').addEventListener('change', updateScheduleCalendar);
    document.getElementById('batch_no').addEventListener('change', updateScheduleCalendar);

    function updateScheduleCalendar() {
        const courseId = document.getElementById('course_name_teacher').value;
        const batchNo = document.getElementById('batch_no').value;
        if (!courseId || !batchNo) return;
        fetch('/dashboard/teacher/get-batch-schedule-days', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    course_id: courseId,
                    batch_no: batchNo
                })
            })
            .then(response => response.json())
            .then(data => {
                if (window.updateCalendarDays && data.success) {
                    window.updateCalendarDays(data.start_date, data.end_date, data.days, data.class_links,
                        data.start_time, courseId, batchNo);
                }
            });
    }
    </script>
</div>
