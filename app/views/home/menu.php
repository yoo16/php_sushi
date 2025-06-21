<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>回転寿司 注文アプリ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-sky-50 text-gray-800 p-4">
    <h1 class="text-2xl font-bold mb-6 text-center p-4"><?= SITE_TITLE ?> (<?= $seat['number'] ?>番テーブル)</h1>

    <!-- モーダル -->
    <div id="modal" class="hidden fixed inset-0 bg-white flex z-50">
        <div id="modal-content" class="w-full m-10"></div>
    </div>
    <div id="modal-overlay" class="hidden fixed inset-0"></div>
    
    <!-- 🔁 カテゴリタブ -->
    <div id="category-tabs" class="flex flex-wrap justify-center mb-6"></div>

    <div class="flex flex-col md:flex-row gap-6">
        <!-- 左側：メニュー -->
        <div class="flex-1" id="menu-area"></div>

        <!-- 右側：注文 -->
        <div class="w-full md:w-72 bg-white p-4 rounded shadow">
            <h2 class="text-center text-xl font-semibold mb-2">
                注文履歴
            </h2>
            <ul id="order-list" class="mb-4 space-y-1"></ul>
            <div id="total" class="my-2 text-right text-lg">合計：¥0</div>
            <button class="w-full bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded" onclick="">
                お会計
            </button>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>

</html>