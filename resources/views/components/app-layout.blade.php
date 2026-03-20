<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Statistik Desa - Beltim</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-700 p-4 text-white shadow-lg">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <span class="font-bold">STATISTIK DESA BELTIM</span>
            <div class="space-x-4">
                <a href="{{ route('admin.index') }}" class="hover:underline">Dashboard</a>
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto mt-10">
        {{ $slot }}
    </main>
</body>
</html>