<x-layout>

    <div class="container fluid">
        <h1 class="display-3 text-center">
            Welcome to AssignMate
        </h1>
        <div class="important-notice my-4 container bg-purple shadow p-3 rounded-3">
            <h4 class="text-center text-white">
                Important notice
            </h4>
            <p class="text-white " style="max-height:100px;overflow-y:scroll">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus, deleniti. Omnis deleniti,
                consectetur praesentium id pariatur quam, iste voluptas sint sapiente ad at officia dolores aliquam
                adipisci numquam? Eligendi, quibusdam?
            </p>
        </div>
        <div class="container mx-auto row justify-content-center align-items-center">
            <div class="col-lg-6">
                <form action="" method="POST" class="sign-in-form">
                    @csrf
                    <h2>Sign In</h2>
                    <div class="form-group">
                        <div class="d-flex form-control my-2 error-message">
                            <div class="bi text-purple bi-person"></div>
                            <input type="text" name="email"
                                placeholder="Enter username or email or phone number..."
                                class="w-100 border-0 outline-none text-purple email">
                        </div>
                        <div class="d-flex form-control my-2 error-message">
                            <div class="bi text-purple bi-lock"></div>

                            <input type="password" name="password" placeholder="Enter Password..."
                                class="w-100 border-0 outline-none text-purple password">
                            <span>
                                <div class="bi text-purple bi-eye"></div>
                            </span>
                        </div>
                        <h6 class="invalid"></h6>
                        <a href="" class="text-purple  text-decoration-none my-2 fw-semibold">
                            Forgot your password?
                        </a><br>
                        <button class="btn sign-in-btn my-2 btn-purple">
                            Sign In
                        </button>
                    </div>


                </form>
            </div>
            <div class="col-lg-6">
                <img width="100%"
                    src="https://png.pngtree.com/png-clipart/20200701/original/pngtree-workspace-office-desk-with-laptop-for-work-from-home-campaign-png-image_5386769.jpg"
                    alt="Sign In assignmate">
            </div>
        </div>
    </div>


</x-layout>
