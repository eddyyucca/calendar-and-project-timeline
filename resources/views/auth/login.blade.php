<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | HRGA Activity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <style>
        body { background: #eef6ff; }
        .login-logo a { color: #0f5fb8; font-weight: 800; }
        .btn-primary { background-color: #0f5fb8; border-color: #0f5fb8; }
        .card { border-radius: .5rem; border: 1px solid #dbeafe; box-shadow: 0 .4rem 1.2rem rgba(15, 95, 184, .12); }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('login') }}"><i class="fas fa-tasks mr-2"></i>HRGA Activity</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Masuk ke workspace daily activity</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                    @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                    @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
                <div class="row align-items-center">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </button>
                    </div>
                </div>
            </form>
            <p class="mb-0 mt-3">
                <a href="{{ route('register') }}">Buat akun baru</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
