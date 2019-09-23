<?php
// zhangwei@danke.com

require __DIR__ . '/../vendor/autoload.php';

use hollodotme\FastCGI\Client;
use hollodotme\FastCGI\Requests\PostRequest;
use hollodotme\FastCGI\SocketConnections\NetworkSocket;
use hollodotme\FastCGI\SocketConnections\UnixDomainSocket;


function echo_usage()
{
    echo "Usage:
  php-fpm-cli.phar 127.0.0.1:9090 path/to/script.php
  php-fpm-cli.phar /var/run/php/php7.3-fpm.sock path/to/script.php
";
}


$socketArg = trim($_SERVER['argv'][1] ?? '');
if (!$socketArg) {
    echo "ERROR: socket arg missing\n";
    echo_usage();
    exit(1);
}

$scriptArg = trim($_SERVER['argv'][2] ?? '');
if (!$scriptArg) {
    echo "ERROR: script arg missing\n";
    echo_usage();
    exit(1);
}


// prepare connection
if (strpos($socketArg, '/') === 0) {
    $connection = new UnixDomainSocket($socketArg);
} else {
    list($host, $port) = explode(':', $socketArg);
    $connection = new NetworkSocket($host, intval($port));
}

$client = new Client();
$request = new PostRequest(realpath($scriptArg), '');
$response = $client->sendRequest($connection, $request);
echo $response->getBody();
