<div class="d-flex justify-content-between align-items-center">
    <h5 class="text-capitalize">
        {{ request()->segment(3) }}
    </h5>
    <form action="">
        <select name="batch_no" id="" class="form-select border-0 text-purple fw-medium bg-transparent">
            @for ($i = 0; $i < 5; $i++)
                <option value="{{ $i + 1 }}" class="  ">
                    <h5>Class {{ $i + 1 }}</h5>
                </option>
            @endfor
        </select>
    </form>
</div>
