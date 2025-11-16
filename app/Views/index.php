<div class="max-w-4xl mx-auto mt-16 text-center">
    <h1 class="text-5xl font-bold text-gray-800 mb-6">
        Welcome to <span class="text-blue-600">MiniFrame</span>
    </h1>
    <p class="text-xl text-gray-600 mb-8">
        A lightweight, fast, and developer-friendly PHP framework.
    </p>
    <div class="bg-gray-100 p-8 rounded-lg inline-block">
        <p class="text-lg mb-4">
            You are currently
            <?php if ($user): ?>
            logged in as <strong><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
            <?php else: ?>
            not logged in
            <?php endif; ?>
            .
        </p>
        <div class="space-x-4">
            <?php if ($user): ?>
            <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition">
                Logout
            </a>
            <?php else: ?>
            <a href="/login" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                Login
            </a>
            <?php endif; ?>
            <a href="https://github.com/mhvefgh/microlite" target="_blank"
                class="bg-gray-800 hover:bg-black text-white px-6 py-3 rounded-lg transition">
                View on GitHub
            </a>
        </div>
    </div>

    <div class="mt-12 text-gray-500">
        <p>Built with love using pure PHP • No dependencies • MVC • Medoo ORM</p>
    </div>
</div>
