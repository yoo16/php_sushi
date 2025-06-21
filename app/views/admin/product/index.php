<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-4">商品一覧</h1>

        <div class="mb-4 text-right">
            <a href="create.php" class="bg-sky-600 text-white px-4 py-2 rounded hover:bg-sky-700">
                商品追加
            </a>
        </div>

        <?php if (!empty($products)): ?>
            <table class="min-w-full text-left table-auto border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">商品名</th>
                        <th class="border px-4 py-2">カテゴリID</th>
                        <th class="border px-4 py-2">価格</th>
                        <th class="border px-4 py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2"><?= htmlspecialchars($product['name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($category_names[$product['category_id']]) ?></td>
                            <td class="border px-4 py-2">¥<?= number_format($product['price']) ?></td>
                            <td class="border px-4 py-2 space-x-2">
                                <a href="edit.php?id=<?= $product['id'] ?>" class="text-blue-600 hover:underline">編集</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>商品が登録されていません。</p>
        <?php endif; ?>
    </div>
</body>
</html>