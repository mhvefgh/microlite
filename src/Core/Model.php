<?php
namespace Src\Core;

use Exception;
use Medoo\Medoo;

/**
 * Base Model class for lightweight ORM functionality.
 * Supports: CRUD, fillable protection, hidden fields, and simple relationships.
 */
abstract class Model
{
    protected static ?Medoo $db = null;

    protected string $table = ''; // Table name
    protected string $primaryKey = 'id'; // Primary key column

    protected array $fillable = []; // Fields allowed for mass assignment
    protected array $hidden = []; // Fields hidden from JSON/export
    protected array $attributes = []; // Current model attributes

    /**
     * Initialize model with optional attributes.
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Inject global Medoo instance (usually from Container).
     */
    public static function setConnection(Medoo $db): void
    {
        self::$db = $db;
    }

    /**
     * Get current Medoo database instance.
     */
    protected static function db(): Medoo
    {
        if (!self::$db) {
            throw new Exception('Database not configured. Set up config/database.php and .env');
        }
        return self::$db;
    }

    /**
     * Fill model attributes safely (only fillable fields allowed).
     */
    public function fill(array $data): self
    {
        if (!empty($this->fillable)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->fillable, true)) {
                    $this->attributes[$key] = $value;
                }
            }
        } else {
            // If fillable not defined, allow all attributes
            $this->attributes = array_merge($this->attributes, $data);
        }
        return $this;
    }

    /**
     * Get dynamic property (magic getter).
     */
    public function __get(string $key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set dynamic property (magic setter).
     */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Return array of attributes (excluding hidden fields).
     */
    public function toArray(): array
    {
        $data = $this->attributes;
        foreach ($this->hidden as $key) {
            unset($data[$key]);
        }
        return $data;
    }

    /**
     * Convert model to JSON safely.
     */
    public function toJson(int $options = JSON_PRETTY_PRINT): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Find a record by primary key.
     */
    public static function find(int|string $id): ?static
    {
        $instance = new static();
        $data = self::db()->get($instance->table, '*', [$instance->primaryKey => $id]);
        return $data ? new static($data) : null;
    }

    /**
     * Get all records from the table.
     */
    public static function all(): array
    {
        $instance = new static();
        $results = self::db()->select($instance->table, '*');
        return array_map(fn($row) => new static($row), $results);
    }

    /**
     * Where query with flexible operator/value.
     */
    // public static function where(string $column, mixed $operatorOrValue, mixed $value = null): array
    // {
    //     $instance = new static();
    //     $op = $value !== null ? $operatorOrValue : '=';
    //     $val = $value ?? $operatorOrValue;

    //     $condition = [$column => [$op => $val]];

    //     $results = self::db()->select($instance->table, '*', $condition);
    //     return array_map(fn($row) => new static($row), $results);
    // }

    public static function where(string $column, mixed $value): array
    {
        $instance = new static();
        $results = self::db()->select($instance->table, '*', [
            $column => $value,
        ]);
        return array_map(fn($row) => new static($row), $results);
    }

    /**
     * Save current model (insert or update based on primary key existence).
     */
    public function save(): bool
    {
        $data = !empty($this->fillable) ? array_intersect_key($this->attributes, array_flip($this->fillable)) : $this->attributes;

        if (isset($this->attributes[$this->primaryKey])) {
            // Update existing record
            $id = $this->attributes[$this->primaryKey];
            unset($data[$this->primaryKey]);

            return self::db()
                ->update($this->table, $data, [$this->primaryKey => $id])
                ->rowCount() > 0;
        }

        // Insert new record
        $id = self::db()->insert($this->table, $data)->id();
        if ($id) {
            $this->attributes[$this->primaryKey] = $id;
            return true;
        }
        return false;
    }

    /**
     * Delete current model record by primary key.
     */
    public function delete(): bool
    {
        if (!isset($this->attributes[$this->primaryKey])) {
            return false;
        }

        return self::db()
            ->delete($this->table, [$this->primaryKey => $this->attributes[$this->primaryKey]])
            ->rowCount() > 0;
    }

    /**
     * Define a hasMany relationship.
     * Example: $user->hasMany(Post::class, 'user_id');
     */
    public function hasMany(string $related, string $foreignKey, string $localKey = 'id'): array
    {
        $relatedModel = new $related();
        $results = self::db()->select($relatedModel->table, '*', [$foreignKey => $this->$localKey]);
        return array_map(fn($row) => new $related($row), $results);
    }

    /**
     * Define a belongsTo relationship.
     * Example: $post->belongsTo(User::class, 'user_id');
     */
    public function belongsTo(string $related, string $foreignKey, string $ownerKey = 'id'): ?object
    {
        $relatedModel = new $related();
        $data = self::db()->get($relatedModel->table, '*', [$ownerKey => $this->$foreignKey]);
        return $data ? new $related($data) : null;
    }
}
