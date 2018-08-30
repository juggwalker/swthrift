<?php
require_once "../vendor/autoload.php";

spl_autoload_register(function ($class) {
    // 项目的命名空间前缀
    // $prefix = 'Foo\\Bar\\';
    $prefix = '';
    $base_dir = __DIR__ . '/gen-php/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 未包含命名空间前缀，立即返回
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});




use call\services\order\OrderServiceClient;



$socket = new \Thrift\Transport\TSocket('127.0.0.1', 8192);
$transport = new \Thrift\Transport\TFramedTransport($socket);
$protocol = new \Thrift\Protocol\TBinaryProtocol($transport);
$transport->open();

$client = new OrderServiceClient($protocol);
echo $client->getOrderId();
echo PHP_EOL;
print_r($client->orderInit(567));
echo PHP_EOL;
var_dump($client->HasId(89));

$transport->close();