<?php
namespace niclasleonbock\React\FTP;

use React\Socket\ServerInterface as SocketServerInterface;
use Evenement\EventEmitter;

class Server extends EventEmitter
{
    protected $io;

    public function __construct(SocketServerInterface $io)
    {
        $this->io = $io;

        $this->io->on('connection', function ($conn) use ($io) {
            $conn->on('data', function ($data) use ($conn) {
                $this->emit('data', array($data, $conn));

                $data = explode(' ', $data);
                if (isset($data[0])) {
                    $cmd = array_shift($data);
                } else {
                    $cmd = $data;
                }

                $this->emit('command', array($cmd, $data, $conn));
                $this->emit('command:'.$cmd, array($data, $conn));
            });
        });
    }

    public function prepare($message)
    {
        $charlist = "\r\n\0";

        return trim($message, $charlist)."\r\n";
    }
}
