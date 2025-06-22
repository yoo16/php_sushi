<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>お会計</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-sky-50 text-gray-800 p-6">
    <h1 class="text-2xl font-bold text-center p-4"><?= SITE_TITLE ?></h1>
    <div id="visit" data-id="<?= $visit_id ?>" class="text-center mb-4">
        会計番号：<?= $visit_id ?>
    </div>

    <ul class="space-y-2 mb-4">
        <?php foreach ($orders as $order): ?>
            <li class="flex items-center bg-white p-3 rounded shadow">
                <img src="<?= htmlspecialchars($order['product_image_path']) ?>" alt="" class="w-16 rounded mr-4">
                <div class="flex-1">
                    <div class="font-semibold"><?= htmlspecialchars($order['product_name']) ?></div>
                    <div>単価：¥<?= $order['price'] ?> × <?= $order['quantity'] ?>点</div>
                </div>
                <div class="text-right font-bold">¥<?= $order['price'] * $order['quantity'] ?></div>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="text-3xl font-bold text-center p-6">
        合計：<?= $total ?>円
        （税込 <?= $total_with_tax ?>円）
    </div>

    <form action="billed.php" method="POST" class="text-center">
        <div class="p-4 font-bold">この内容で会計しますか？</div>
        <div class="flex justify-center space-x-4">
            <button class="bg-sky-600 text-white px-6 py-3 rounded text-lg">
                確定
            </button>
            <a href="menu.php" class="border border-sky-600 text-sky-600 px-6 py-3 rounded text-lg">もどる</a>
        </div>
    </form>

</body>

</html>