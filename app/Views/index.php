<!doctype html>
<html>
<head><meta charset="utf-8"><title>Home</title></head>
<body>
    <h1>Welcome to Microframework</h1>
    <p>Users (from DB):</p>
    <ul>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <li><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No users found.</li>
        <?php endif; ?>
    </ul>
</body>
</html>
