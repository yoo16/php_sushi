<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>座席番号のご案内</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <main class="container mx-auto my-10">
        <h1 class="text-center text-3xl font-bold p-6"><?= SITE_TITLE ?></h1>
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <h2 class="text-2xl font-bold mb-6">あなたの座席番号</h2>
            <div class="text-green-700 mb-6">
                <form action="reserve.php" method="post">
                    <select name="seat_id" id="seat_id"
                        class="border border-gray-300 text-gray-900 text-lg rounded-lg p-2.5">
                        <?php foreach ($seats as $seat): ?>
                            <option value="<?= $seat['id'] ?>">
                                <?= $seat['number'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="mt-4">
                        <button class="inline-block bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-6 py-3 rounded shadow">
                            注文をはじめる
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>