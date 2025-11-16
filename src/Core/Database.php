<?php
namespace Src\Core;

use Medoo\Medoo;
use Throwable;

class Database
{
    protected Medoo $db;

    public function __construct(Container $container)
    {
        $config = $container->get('config')['database'];

        // Initialize Medoo
        $this->db = new Medoo([
            'type' => $config['type'],
            'host' => $config['host'],
            'database' => $config['database'],
            'username' => $config['username'],
            'password' => $config['password'],
            'charset' => $config['charset'],
            'port' => $config['port'],
            'prefix' => $config['prefix'],
            'logging' => $config['logging'],
        ]);
    }

    /**
     * Return Medoo instance.
     */
    public function getConnection(): Medoo
    {
        return $this->db;
    }

        /**
     * Helper methods for simple database operations
     */
    public function select(string $table, string|array $columns = '*', array $where = []): array
    {
        return $this->db->select($table, $columns, $where);
    }

    public function insert(string $table, array $data): int
    {
        return $this->db->insert($table, $data)->id();
    }

    public function update(string $table, array $data, array $where): int
    {
        return $this->db->update($table, $data, $where)->rowCount();
    }

    public function delete(string $table, array $where): int
    {
        return $this->db->delete($table, $where)->rowCount();
    }


    /**
     * Execute a transaction safely.
     * Example:
     * $db->transaction(function($db) {
     *     $db->insert('users', [...]);
     * });
     */
    public function transaction(callable $callback): bool
    {
        $pdo = $this->db->pdo;
        try {
            $pdo->beginTransaction();
            $callback($this->db);
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Get the raw PDO instance.
     */
    public function pdo(): \PDO
    {
        return $this->db->pdo;
    }

    /**
     * Run a raw SQL query.
     * Example:
     * $db->query("SELECT * FROM users WHERE id = :id", ['id' => 1]);
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Log the last executed query (if logging enabled).
     */
    public function getLog(): array
    {
        return $this->db->log();
    }
}
