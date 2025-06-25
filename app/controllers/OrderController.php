<?php

class OrderController
{
    /**
     * 注文の一覧を表示
     *
     * @return void
     */
    public function index()
    {
        // ビューを読み込む
        require VIEW_DIR . 'order/index.php';
    }

    /**
     * 注文の詳細を表示
     *
     * @param int $orderId
     * @return void
     */
    public function show($orderId)
    {
        require VIEW_DIR . 'order/show.php';
    }
}