@if ($students->hasPages())
<div class="d-flex justify-content-between student-pagination">
    {{ $students->links("pagination::bootstrap-4") }}
</div>
@endif