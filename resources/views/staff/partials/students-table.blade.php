@foreach ($students as $student)
    <tr>
        <td>{{ $student->id }}</td>
        <td>{{ $student->name }}</td>
        <td>{{ $student->email }}</td>
        <td>{{ $student->studentBatch->batch_no ?? 'N/A' }}</td>
        <td>{{ $student->studentCourse->course_name ?? 'N/A' }}</td>
        <td>
            <button class="btn btn-danger delete-student" data-id="{{ $student->id }}">Delete</button>
        </td>
        <td>
            <button class="btn btn-primary edit-student" data-id="{{ $student->id }}">Edit</button>
        </td>
    </tr>
@endforeach
