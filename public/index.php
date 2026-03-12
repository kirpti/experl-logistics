<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Kurulum kontrolü
if (!file_exists(dirname(__DIR__).'/vendor/autoload.php')) {
    http_response_code(503);
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Experl Logistics</title>
    <style>body{font-family:Arial,sans-serif;background:#090e1a;color:#e2eaf5;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
    .box{background:#14213d;border:1px solid #1e304f;border-radius:16px;padding:48px;text-align:center;max-width:480px;}
    h2{color:#f59e0b;margin-bottom:12px;} p{color:#5c7a9e;font-size:14px;line-height:1.6;}</style></head>
    <body><div class="box"><h2>⚙️ Kurulum Devam Ediyor</h2>
    <p>Sistem bağımlılıkları yükleniyor.<br>Lütfen birkaç dakika bekleyip sayfayı yenileyin.</p>
    <p style="margin-top:16px;font-size:12px;color:#374151">Eğer bu mesaj 5 dakikadan uzun süredir görünüyorsa<br>cPanel Git deploy işlemini kontrol edin.</p>
    </div></body></html>';
    exit;
}

// Bakım modu kontrolü
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
