<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline – SUMITY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#059669">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-800 to-cyan-900 flex items-center justify-center p-4">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-xl mb-6">
            <img src="/image/logo.png" alt="SUMITY" class="w-16 h-16 object-cover">
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">You're Offline</h1>
        <p class="text-emerald-200 mb-6 max-w-sm">
            Please check your internet connection and try again.
        </p>
        <button onclick="location.reload()"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-xl font-semibold transition-colors shadow-lg">
            <i class="fas fa-rotate mr-2"></i> Retry
        </button>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</body>
</html>
