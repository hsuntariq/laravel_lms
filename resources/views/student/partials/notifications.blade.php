<aside class="p-3">
    <div class="card border-0 p-xl-4 p-3 shadow rounded-3">
        <h4>Latest</h4>
        <div id="latest-tasks-container">
            <!-- Tasks will be loaded here dynamically -->
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</aside>

<script>
$(document).ready(function() {
    function fetchLatestTasks() {
        $.ajax({
            url: '/dashboard/student/get-latest-assignments', // Update with your actual endpoint
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    renderTasks(response.data);
                } else {
                    $('#latest-tasks-container').html(
                        '<p class="text-muted">No upcoming tasks found</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching tasks:', error);
                $('#latest-tasks-container').html(
                    '<div class="alert alert-danger">Failed to load tasks. Please try again later.</div>'
                );
            }
        });
    }

    function renderTasks(tasks) {
        const container = $('#latest-tasks-container');
        container.empty();

        tasks.forEach(task => {
            const deadline = new Date(task.deadline);
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
            ];
            const day = deadline.getDate();
            const month = monthNames[deadline.getMonth()];

            // Format time (e.g., "16:45")
            const hours = deadline.getHours().toString().padStart(2, '0');
            const minutes = deadline.getMinutes().toString().padStart(2, '0');
            // const timeString = `${hours}:${minutes}`;

            const taskHtml = `
                <div class="d-flex flex-sm-row align-items-start align-items-sm-center gap-3 my-3">
                    <div class="rounded-3  p-3 py-2 text-center" style="background:#EDE8F5; min-width: 60px;">
                        <h6 class="m-0">${day}</h6>
                        <p class="m-0">${month}</p>
                    </div>
                    <div class="flex-grow-1">
                            <h6 class="m-0">${task.topic.length > 10 ? task.topic.slice(0, 10) + '...' : task.topic}</h6>                        <div class="text-danger d-flex gap-1 align-items-center mt-1">
                            <div class="rounded-circle bg-danger" style="width: 5px;height: 5px;"></div>
                            <small class='text-capitalize'>${task.type || 'Assignment'}</small>
                        </div>
                    </div>
                </div>
            `;

            container.append(taskHtml);
        });
    }

    // Initial fetch
    fetchLatestTasks();

});
</script>
