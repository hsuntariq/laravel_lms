@foreach ($batches as $batch)
<tr>
    <td>{{ $batch->id }}</td>
    <td>{{ $batch->batch_number }}</td>
    <td>{{ $batch->teachers->name ?? 'N/A' }}</td>
    <td>{{ $batch->course->course_name ?? 'N/A' }}</td>
    <td>{{ $batch->duration }}</td>
    <td>
        <button class="btn btn-purple btn-sm">Update</button>
    </td>
    <td>
        <button class="btn btn-danger btn-sm">Delete</button>
    </td>
</tr>
@endforeach