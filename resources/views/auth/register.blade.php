<!doctype html>
<html lang="en">

<head>
    <title>Register</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" />

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/phosphor/duotone/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />
</head>

<body>
    <div class="auth-main">
        <div class="auth-wrapper v3">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="auth-header">
                                <h2 class="text-secondary mt-5"><b>Create an Account</b></h2>
                                <p class="f-16 mt-2">Enter your details to register</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="name" required placeholder="Full Name" />
                                <label>Full Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" name="email" required placeholder="Email address" />
                                <label>Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="password" required placeholder="Password" />
                                <label>Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password" />
                                <label>Confirm Password</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select class="form-control" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                    <option value="3">Other</option>
                                </select>
                                <label>Gender</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="mobile" required placeholder="Mobile Number" />
                                <label>Mobile Number</label>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-secondary">Register</button>
                            </div>
                        </form>
                        <div class="d-grid mt-4">
                            <a href="{{ url('auth/google') }}" class="btn btn-danger">
                                <i class="fab fa-google"></i> Sign Up with Google
                            </a>
                        </div>
                        <p class="text-center mt-3">Already have an account? <a href="{{ route('login') }}" class="text-secondary">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required JS -->
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
    </script>
    <script>
        font_change('Roboto');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_caption_change('true');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change('preset-1');
    </script>
</body>

</html>
