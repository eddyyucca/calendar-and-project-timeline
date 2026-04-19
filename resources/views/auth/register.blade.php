<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | HRGA Activity</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <style>
        body { background: #eef6ff; }
        .register-logo a { color: #0f5fb8; font-weight: 800; }
        .btn-primary { background-color: #0f5fb8; border-color: #0f5fb8; }
        .card { border-radius: .5rem; border: 1px solid #dbeafe; box-shadow: 0 .4rem 1.2rem rgba(15, 95, 184, .12); }
    </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ route('register') }}"><i class="fas fa-user-plus mr-2"></i>HRGA Activity</a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Buat akun pengguna</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama" required autofocus>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
                    @error('name')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                    @error('email')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                    @error('password')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-check mr-1"></i> Register
                </button>
            </form>
            <p class="mb-0 mt-3">
                <a href="{{ route('login') }}">Sudah punya akun</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>
