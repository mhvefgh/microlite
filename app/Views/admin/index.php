<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <script src="/assets/tailwindcss-3.4.17.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-blue-600">Welcome to Microframework</h1>
        <p class="text-lg mb-4">Admin Users</p>

        <?php if (!empty($users)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg">
                    <thead class="bg-blue-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">#</th>
                            <th class="py-3 px-4 text-left">Name</th>
                            <th class="py-3 px-4 text-left">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $index => $user): ?>
                            <tr class="<?= $index % 2 === 0 ? 'bg-gray-50' : 'bg-white' ?> hover:bg-blue-50">
                                <td class="py-2 px-4 border-t"><?= $index + 1 ?></td>
                                <td class="py-2 px-4 border-t font-medium"><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="py-2 px-4 border-t text-gray-600"><?= htmlspecialchars($user['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-4 bg-yellow-100 text-yellow-700 rounded-md">
                No users found.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
