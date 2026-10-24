
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body>
    <h1>Verifikasi Email</h1>

    <p>
        Silakan periksa email kamu dan klik tautan verifikasi
        untuk mengaktifkan akun.
    </p>

    @if (session('message'))
        <p>{{ session('message') }}</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Kirim Ulang Email Verifikasi</button>
    </form>
</body>
</html>
