<x-layout>
    <div class="d-flex  align-items-center justify-content-center min-vh-100">

        <div class="container px-4 pt-5">
            <h1 class="display-5 text-center text-purple mb-5 fade-in">
                Welcome to AssignMate
            </h1>

            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6">
                    <form action="" method="POST" class="sign-in-form">
                        @csrf
                        <h2 class="text-center fw-semibold mb-4">Sign In</h2>
                        <div class="form-group">
                            <div class="d-flex form-control my-3 error-message align-items-center">
                                <div class="bi bi-person text-purple me-3"></div>
                                <input type="text" name="email" placeholder="Enter username or email or phone number..."
                                    class="w-100 border-0 outline-none text-purple px-3 py-2 email">
                            </div>
                            <div class="d-flex form-control my-3 error-message align-items-center">
                                <div class="bi bi-lock text-purple me-3"></div>
                                <input type="password" name="password" id="password" placeholder="Enter Password..."
                                    class="w-100 border-0 outline-none text-purple px-3 py-2 password">
                                <span class="cursor-pointer" onclick="togglePassword()">
                                    <div class="bi bi-eye text-purple"></div>
                                </span>
                            </div>
                            <h6 class="invalid"></h6>

                            <button type="submit" class="btn sign-in-btn w-100 my-3 btn-purple py-2">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6 d-none d-md-block fade-in">
                    <img width="100%"
                        src="https://png.pngtree.com/png-clipart/20200701/original/pngtree-workspace-office-desk-with-laptop-for-work-from-home-campaign-png-image_5386769.jpg"
                        alt="Sign In assignmate" class="rounded-3">
                </div>
            </div>
        </div>
        <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('#password + span .bi');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            }
        }
        </script>

    </div>
</x-layout>
