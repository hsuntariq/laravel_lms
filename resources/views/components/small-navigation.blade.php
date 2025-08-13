@php
$userId = auth()->id();
$items = [
[
'label' => 'Dashboard',
'icon' => asset('images/icons/dashboard.png'),
'link' => route('student-dashboard', ['id' => $userId])
],
[
'label' => 'Courses',
'icon' => asset('images/icons/courses.png'),
'link' => route('student-courses', ['id' => $userId])
],
[
'label' => 'Assignments',
'icon' => asset('images/icons/assignments.png'),
'link' => route('student-assignments', ['id' => $userId])
],
[
'label' => 'Marks',
'icon' => asset('images/icons/marks.png'),
'link' => route('student-marks', ['id' => $userId])
],
[
'label' => 'Attendance',
'icon' => asset('images/icons/attendance.png'),
'link' => route('student-attendance', ['id' => $userId])
],
[
'label' => 'Settings',
'icon' => asset('images/icons/settings.png'),
'link' => route('student-settings', ['id' => $userId])
],
];
@endphp

<div class="container d-block d-md-none col-11 mx-auto my-4 shadow border-0 py-5 card">
    <div class="row row-cols-3 g-3 justify-content-center">

        @foreach ($items as $item)
        <a href="{{ $item['link'] }}"
            class="col position-relative text-dark text-decoration-none d-flex flex-column align-items-center">
            <div class="card shadow-sm p-3 text-center border-0" style="min-width: 90px;">
                <div class="position-relative">
                    <div class="rounded-circle border d-flex justify-content-center align-items-center mx-auto mb-2"
                        style="width: 40px; height: 40px;">
                        <img src="{{ $item['icon'] }}" alt="{{ $item['label'] }}" width="20" height="20">
                    </div>
                    @if ($item['label'] === 'Assignments')
                    <span id="assignment-badge"
                        class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"
                        style="display: ; font-size: 10px;">
                        <span class="badge-count"></span>
                    </span>
                    @endif
                </div>
                <div style="font-size: 13px;">{{ $item['label'] }}</div>
            </div>
        </a>
        @endforeach

        {{-- Logout --}}
        <div class="col d-flex flex-column align-items-center mt-2">
            <div class="rounded-circle border d-flex justify-content-center align-items-center mb-2"
                style="width: 40px; height: 40px;">
                <img src="{{ asset('images/icons/logout.webp') }}" alt="Logout" width="20" height="20">
            </div>
            <form action="/sign-out" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger px-3">Logout</button>
            </form>
        </div>
    </div>
</div>

<!-- Assignment Badge Script -->
<x-jquery />
<script>
let userId = window.location.pathname.split('/').pop();

$.ajax({
    url: `/dashboard/student/assignments-get/${userId}`,
    type: "GET",
    beforeSend: function() {
        $('.badge-count').html(`<img src='/assets/images/loading.gif' width='10px' height='10px'>`);
    },
    success: function(response) {
        let assignments = response?.assignments || [];
        const pending = assignments.filter(a => {
            const currentDate = new Date();
            const deadline = new Date(a.deadline);
            return currentDate <= deadline && (!a.answer || a.answer.length === 0);
        });

        const count = pending.length;
        $('.badge-count').html(count);
        if (count > 0) {
            $('#assignment-badge').show();
        } else {
            $('#assignment-badge').hide();
        }
    },
    error: function(xhr) {
        console.error("Assignment fetch error:", xhr.statusText);
    }
});
</script>
