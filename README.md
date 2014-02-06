react-ftp
=========
Simple implementation of ftp syntax for react. There's no single command implemented, it's just a base.

Example
=======
```php
<?php
require __DIR__.'/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$ftp = new niclasleonbock\React\FTP\Server($socket);

// client connected
$socket->on('connection', function ($conn) use ($ftp) {
    print('connection: from '.$conn->getRemoteAddress()."\r\n");

    $conn->write($ftp->prepare("220 Welcome to FTP Server"));
});

// command received
$ftp->on('command', function ($cmd, $data, $conn) {
    print('command: '.$cmd.' '.implode(' ', $data)."\r\n");
});

// command handler, best point to implement parts of RfC 959 (or, if you're keen on it, the entire RfC)
$ftp->on('command:USER', function ($data, $conn) use ($ftp) {
    $conn->write($ftp->prepare('230 OK.')); // anonymous login
});

// raw data
$ftp->on('data', function ($data, $conn) {
    print('raw: '.$data."\r\n");
});

$socket->listen(1337);
$loop->run();

```
