@foreach ($batches as $batch)
<tr>
    <td>{{ $batch->id }}</td>
    <td>{{ $batch->batch_no }}</td>
    <td>{{ optional($batch->teachers)->name ?? 'N/A' }}</td> <!-- Use optional() to prevent errors -->
    <td>{{ optional($batch->course)->course_name ?? 'N/A' }}</td> <!-- Use optional() to prevent errors -->
    <td>{{ $batch->course->course_duration }} months</td> <!-- Adjust according to your actual data -->
    <td>
        <button class="btn btn-purple btn-sm update-btn" data-id="{{ $batch->id }}" data-bs-toggle="modal" data-bs-target="#updateBatchModal">
            Update
        </button>
    </td>
    <td>
        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $batch->id }}">
            Delete
        </button>
    </td>

</tr>
@endforeach