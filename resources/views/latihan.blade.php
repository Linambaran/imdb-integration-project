<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tolong Saya Bu Dewi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-100 flex items-center justify-center h-screen">
   <div class="bg-white p-10 rounded-lg shadow-xl">
    <h1 class="text-3xl font-bold text-blue-600">Berhasil Connect!</h1>
    
    <p class="text-gray-700 mt-4">
        Database yang kamu pakai adalah:
    </p>
    
    <div class="mt-2 p-3 bg-gray-100 rounded border border-gray-300 font-mono text-sm text-red-600">
        {{ $info }}
    </div>
    </div>
</body>
</html>