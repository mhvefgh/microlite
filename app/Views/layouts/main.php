<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'MiniFrame' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-600">MiniFrame</h1>
            <nav>
                <?php if (auth()): ?>
                    <span>Hello, <?= e(auth()->name) ?></span>
                    <a href="/logout" class="ml-4 text-red-600 hover:underline">Logout</a>
                <?php else: ?>
                    <a href="/login" class="text-blue-600 hover:underline">Login</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-8 mt-16">
        <p>MiniFrame • Open Source • Made with PHP</p>
    </footer>
</body>
</html>