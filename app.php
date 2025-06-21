<?php
// 設定ファイルを読み込み
require_once "env.php";

// セッション開始
session_start();
session_regenerate_id(true);

// アプリケーションのルートディレクトリパス
const BASE_DIR = __DIR__;
// app/ ディレクトリパス
const APP_DIR = __DIR__ . "/app/";
// lib/ ディレクトリパス
const LIB_DIR = __DIR__ . "/lib/";
// components/ ディレクトリパス
const COMPONENT_DIR = __DIR__ . "/components/";

// upload image base path
const IMAGE_BASE = "images/products/";
// upload image dir
const PRODUCTS_IMAGE_DIR = __DIR__ . IMAGE_BASE;

// ライブラリ読み込み
require_once LIB_DIR . 'Database.php';
require_once LIB_DIR . 'Sanitize.php';
require_once LIB_DIR . 'File.php';

// モデルクラスの読み込み
require_once APP_DIR . 'models/Product.php';
require_once APP_DIR . 'models/Category.php';
require_once APP_DIR . 'models/Seat.php';
require_once APP_DIR . 'models/Visit.php';
require_once APP_DIR . 'models/Order.php';

// コントローラークラスの読み込み
require_once APP_DIR . 'controllers/HomeController.php';
require_once APP_DIR . 'controllers/admin/CategoryController.php';
require_once APP_DIR . 'controllers/admin/ProductController.php';

if (!defined('BASE_URL')) define('BASE_URL', getBaseUrl());

function getBaseUrl()
{
    $documentRoot = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']));
    $directory = str_replace('\\', '/', __DIR__);
    $basePath = str_replace($documentRoot, '', $directory);
    // BASE_URL を定義（常にルートからの相対パス）
    return rtrim($basePath, '/') . '/';
}