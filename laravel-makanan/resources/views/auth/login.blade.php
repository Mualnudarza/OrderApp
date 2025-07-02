<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. DWI WIRA USAHA BAKTI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Variabel CSS dari home.blade.php untuk konsistensi */
        :root {
            --dominant-navy: #060058;
            --primary-bg-start: #1A324C;
            --primary-bg-end: #060058;
            --card-bg: #ffffff;
            --text-dark: var(--dominant-navy);
            --text-light: #546E7A;
            --accent-color: #AEC6CF;
            --border-light: #DAE1E8;
            --shadow-subtle: rgba(6, 0, 88, 0.08);
            --input-border: #C8D4E2;
            --input-focus: #7FB3D5;
            --button-primary: #5C6BC0;
            --button-primary-hover: #4a5a9e;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, var(--primary-bg-start), var(--primary-bg-end));
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            color: var(--text-dark);
        }

        .auth-container {
            background-color: var(--card-bg);
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px var(--shadow-subtle);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px; /* Lebar maksimum container */
            text-align: center;
        }

        .auth-container h2 {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 600;
        }

        .form-control {
            border-radius: 0.75rem;
            border: 1px solid var(--input-border);
            padding: 0.8rem 1rem;
            font-size: 1rem;
            color: var(--text-dark);
            background-color: #f8f9fa;
        }

        .form-control:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 0.25rem rgba(92, 107, 192, 0.25); /* Menggunakan primary-color dengan opacity */
        }

        .btn-primary {
            background-color: var(--button-primary);
            border-color: var(--button-primary);
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--button-primary-hover);
            border-color: var(--button-primary-hover);
        }

        .text-muted {
            color: var(--text-light) !important;
        }

        .alert {
            border-radius: 0.75rem;
            font-size: 0.9rem;
        }

        a {
            color: var(--button-primary);
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            color: var(--button-primary-hover);
            text-decoration: underline;
        }

        /* Styling untuk Tab Error */
        .error-tab {
            background-color: #f8d7da; /* Warna latar belakang merah muda untuk error */
            color: #721c24; /* Warna teks merah gelap */
            border: 1px solid #f5c6cb;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            text-align: left;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .error-tab .bi {
            font-size: 1.5rem;
            margin-right: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>

        {{-- Tab Error untuk validasi --}}
        @if ($errors->any())
            <div class="error-tab" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 text-start">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <p class="text-muted">Belum punya akun? <a href="/register">Daftar di sini</a></p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>