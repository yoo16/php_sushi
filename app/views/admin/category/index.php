<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>カテゴリ一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">📂 カテゴリ一覧</h1>

        <div class="mb-4 text-right">
            <a href="create.php" class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-700">
                カテゴリ追加
            </a>
        </div>

        <?php if (count($categories) > 0): ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($categories as $category): ?>
                    <li class="flex justify-between items-center py-3">
                        <span class="text-lg"><?= htmlspecialchars($category['name']) ?></span>
                        <div class="space-x-2">
                            <a href="edit.php?id=<?= $category['id'] ?>" class="text-blue-600 hover:underline">編集</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>カテゴリが登録されていません。</p>
        <?php endif; ?>
    </div>
</body>

</html>