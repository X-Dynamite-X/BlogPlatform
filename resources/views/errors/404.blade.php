<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة غير موجودة</title>
</head>
<body>
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-4xl font-bold text-red-600">404</h1>
        <p class="text-xl text-gray-700">Page Not Found</p>
        <router-link to="/" class="mt-4 text-blue-500 hover:underline">Go back to Home</router-link>
      </div>
</body>
</html>
