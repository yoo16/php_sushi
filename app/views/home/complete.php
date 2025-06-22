<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ご来店ありがとうございました</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-sky-50 text-gray-800 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow text-center space-y-6 max-w-md">
        <h1 class="text-2xl font-bold text-center p-4"><?= SITE_TITLE ?></h1>
        <h2 class="text-2xl font-bold text-sky-600">ありがとうございました！</h2>
        <p class="text-lg">お会計は <span class="font-semibold text-red-500">レジ</span> にてお願いいたします。</p>
        <p class="text-gray-600">またのご利用を心よりお待ちしております。</p>
        <a href="./" class="inline-block mt-4 text-sky-600 hover:underline">トップページへ戻る</a>
    </div>
</body>

</html>