<?php
namespace Src\Core;

use Medoo\Medoo;

class Database
{
    protected Medoo $db;

    public function __construct(Container $container)
    {
        $config = $container->get('config')['database'];

        $this->db = new Medoo([
            'type' => $config['type'],
            'host' => $config['host'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => $config['charset'],
            'port' => $config['port'],
        ]);
    }

    public function getConnection(): Medoo
    {
        return $this->db;
    }
}
