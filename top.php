<?php
// 例: 1〜12番の座席からランダムに1つ選ぶ
$seat = rand(1, 12);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>座席番号のご案内</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-green-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-6">あなたの座席番号はこちら</h1>
        <div class="text-5xl font-extrabold text-green-700 mb-6">
            <?= $seat ?>
        </div>
        <a href="menu.php?seat=<?= $seat ?>"
            class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-6 py-3 rounded shadow">
            注文をはじめる
        </a>
    </div>
</body>

</html>