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

use call\services\order\OrderServiceIf;
use call\services\order\OrderServiceProcessor;
use call\services\order\Order;

class OrderServiceImpl implements OrderServiceIf
{
    public function getOrderId(){
        echo 'getOrderId:321'.PHP_EOL;
        return 321;
    }

    public function orderInit($orderId){
        echo 'orderInit:param-->'.$orderId.PHP_EOL;
        $ret = array('orderId' => 12345, 'remark' => 'good order');
        return new Order($ret);
    }

    public function HasId($id){
        echo 'HasId:false'.PHP_EOL;
        return false;
    }

}

$service = new OrderServiceImpl();
$processor = new OrderServiceProcessor($service);

// 服务组名称
$serviceName = 'testserv';

//swooler_server 里的配置选项参数，worker_num根据实际调用情况调节
$setting = [
    'worker_num' => 4,
    'log_file' => __DIR__.'/swoole.log',
    'pid_file' => __DIR__.'/thrift.pid',
];
$socket_tranport = new \SwooleThrift\TSwooleServerTransport('0.0.0.0', 8192, $setting);
$out_factory = $in_factory = new \Thrift\Factory\TTransportFactory();
$out_protocol = $in_protocol = new \Thrift\Factory\TBinaryProtocolFactory();

$server = new \SwooleThrift\TSwooleServer($processor, $socket_tranport, $in_factory, $out_factory, $in_protocol, $out_protocol);
$server->serve($serviceName);