@if ($students->count() > 0)
@foreach ($students as $student)
<tr>
    <td>{{ $student->id }}</td>
    <td>{{ $student->name }}</td>
    <td>{{ $student->email }}</td>
    <td>{{ optional($student->studentBatch)->batch_no ?? 'N/A' }}</td>
    <td>{{ optional($student->studentCourse)->name ?? 'N/A' }}</td>
    <td>
        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $student->id }}">Delete</button>
    </td>
    <td>
        <button class="btn btn-primary btn-sm edit-btn" data-id="{{ $student->id }}">Edit</button>
    </td>
</tr>
@endforeach
@else
<tr>
    <td colspan="7" class="text-center">No students found in this batch.</td>
</tr>
@endif