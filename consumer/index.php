<?php

require_once __DIR__ . '/vendor/autoload.php';

Class Consumer {

    const QUEUE_NAME = 'api.queue';
    const TYPE_AUTH = 'AUTH';
    const TYPE_RECOVER = 'RECOVER';

    private $connection;
    private $channel;
    private $db;

    function __construct() {
        $connected = false;

        while (!$connected) {
            try {
                $this->connection = new \PhpAmqpLib\Connection\AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
                $this->channel = $this->connection->channel();
            } catch (\Exception $ex) {
                echo ' ... waiting to connect', "\n";
                sleep(10);
                continue;
            }

            echo ' [!] Connected', "\n";
            $connected = true; 
        }

        try {
            $this->db = new PDO('mysql:host=localhost;dbname=database', 'root', 'root');
        } catch (PDOException $ex) {
            echo ' [!] Database Exception : ' . $ex->getMessage(), "\n";
            die();
        }
    }

    private function auth($username, $password)
    {
        $query = $this->database->prepare("SELECT * from users WHERE username=:username"); 
        $query->bindParam(':username', $username);
        $query->execute();
        $user = $query->fetch();

        return !empty($user) && $user['password'] === $password ? true : false;
    }

    private function recoverPassword($email)
    {
        $query = $this->database->prepare("SELECT * from users WHERE id=1"); 
        $query->execute(); 
        $user = $query->fetch();

        if(!empty($user)) {
            mail($email, "Recover Account", "Account:\n Username: {$user['username']}\n Password: {$user['password']}");
        }
    }

    public function listen()
    {
        $this->channel->queue_declare(
            $queue = self::QUEUE_NAME,
            $passive = false,
            $durable = true,
            $exclusive = false,
            $auto_delete = false,
            $nowait = false,
            $arguments = null,
            $ticket = null
        );
        
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        
        $callback = function($data) {
            echo " [x] Received ", $data->body, "\n";
            $job = json_decode($data->body, true);

            $type = $job['type'] ?? null;

            switch ($type) {
                case self::TYPE_AUTH:
                    $this->auth($job['username'], $job['password']);
                break;

                case self::TYPE_RECOVER:
                    $this->recoverPassword($job['email']);
                break;
            }

            echo " [x] Done", "\n";
            $data->delivery_info['channel']->basic_ack($data->delivery_info['delivery_tag']);
        };
        
        $this->channel->basic_qos(null, 1, null);
        
        $this->channel->basic_consume(
            $queue = self::QUEUE_NAME,
            $consumer_tag = '',
            $no_local = false,
            $no_ack = false,
            $exclusive = false,
            $nowait = false,
            $callback
        );
        
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
        
        $this->channel->close();
        $this->connection->close();
        
        echo ' [-] Connection Closed', "\n";
    }
}

/**
 * Consumer Worker
 */
(new Consumer())->listen();
