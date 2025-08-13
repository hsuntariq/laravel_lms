<x-teacher-dashboard-layout>


    <section class="col-xl-10 col-lg-7 p-3">
        <div class="max-width mb-5">
            <h1>Basic Information</h1>
            <div class="underline"></div>
        </div>
        <section class="row">
            <div class="col-sm-6">
                <img style="object-fit: contain;border:1px solid #8338eb;" width="200px" height="200px"
                    class="rounded-circle user-image d-block mx-auto "
                    src="{{auth()->user()->image ? '/storage/' . auth()->user()->image : "https://static.vecteezy.com/system/resources/thumbnails/005/545/335/small/user-sign-icon-person-symbol-human-avatar-isolated-on-white-backogrund-vector.jpg"}}"
                    alt="Assign Mate user image">
            </div>
            <div class="col-sm-6 d-flex flex-column gap-2">
                <h2 class="user-name text-capitalize">
                    {{auth()->user()->name}}
                </h2>
                @if (auth()->user()->role != 'teacher')
                <p class="text-secondary">
                    Batch no:{{auth()->user()->batch_assigned}}<br>
                </p>
                @endif
                <p class="text-secondary">
                    Email:{{auth()->user()->email}}<br>
                </p>



                @include('teacher.partials.update-modal')
            </div>
        </section>
    </section>
</x-teacher-dashboard-layout>
