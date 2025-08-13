<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #8338EB;
        --primary-hover: #6b2ec7;
    }

    body {
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        overflow: hidden;
        position: relative;
    }

    .reset-password-card {
        max-width: 400px;
        width: 100%;
        padding: 2rem;
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        z-index: 10;
        position: relative;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(131, 56, 235, 0.25);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .back-to-login {
        text-decoration: none;
        color: var(--primary-color);
    }

    .back-to-login:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .blobs-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .blob {
        position: absolute;
        background: radial-gradient(circle, rgba(131, 56, 235, 0.3) 0%, rgba(131, 56, 235, 0) 70%);
        border-radius: 50%;
        animation: float 20s infinite ease-in-out;
        opacity: 0.6;
    }

    .blob:nth-child(1) {
        width: 300px;
        height: 300px;
        top: -10%;
        left: 10%;
        animation-duration: 15s;
    }

    .blob:nth-child(2) {
        width: 200px;
        height: 200px;
        top: 60%;
        right: 15%;
        animation-duration: 18s;
        animation-delay: 2s;
    }

    .blob:nth-child(3) {
        width: 250px;
        height: 250px;
        bottom: -5%;
        left: 30%;
        animation-duration: 22s;
        animation-delay: 4s;
    }

    @keyframes float {
        0% {
            transform: translate(0, 0) scale(1);
        }

        25% {
            transform: translate(50px, 50px) scale(1.1);
        }

        50% {
            transform: translate(-30px, 70px) scale(0.9);
        }

        75% {
            transform: translate(20px, -50px) scale(1.05);
        }

        100% {
            transform: translate(0, 0) scale(1);
        }
    }

    @media (max-width: 576px) {
        .reset-password-card {
            margin: 1rem;
            padding: 1.5rem;
        }

        .blob:nth-child(1) {
            width: 150px;
            height: 150px;
            top: 5%;
            left: 5%;
        }

        .blob:nth-child(2) {
            width: 100px;
            height: 100px;
            top: 70%;
            right: 5%;
        }

        .blob:nth-child(3) {
            width: 120px;
            height: 120px;
            bottom: 5%;
            left: 20%;
        }
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
    </style>
</head>

<body>
    <div class="blobs-container">
        <div class="blob"></div>
        <div class="blob"></div>
        <div class="blob"></div>
    </div>
    <div class="reset-password-card">
        <h2 class="text-center mb-4 text-primary">Reset Your Password</h2>
        <p class="text-center text-muted mb-4">Enter your new password below to regain access to your account.</p>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e)
                <div>{{ $e }}</div>
                @endforeach
            </div>
            @endif

            <div class="mb-3">
                <label for="passwordInput" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" id="passwordInput"
                    placeholder="Enter new password" required />
                @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="passwordConfirmationInput" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="passwordConfirmationInput"
                    placeholder="Confirm new password" required />
                @error('password_confirmation')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
        </form>
        <div class="text-center">
            <a href="{{ route('login') }}" class="back-to-login">Back to Login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
