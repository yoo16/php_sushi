<?php
require_once __DIR__ . '/app.php';

use App\Controllers\HomeController;

$controller = new HomeController();
$controller->reserve();
