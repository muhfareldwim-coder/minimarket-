<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <h1 class="text-3xl font-bold mb-4">
                Dashboard Admin
            </h1>

            <p class="mb-4">
                Selamat datang, {{ auth()->user()->name }}
            </p>

            <p class="mb-6">
                Role: {{ auth()->user()->role }}
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>

</body>
</html>
