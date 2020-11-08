<?php


final class DB
{
    /** @var PDO */
    private $pdo = null;

    /** @var DB */
    private static $instance = null;

    public static function getInstance(array $config = null) : DB {
        if(!self::$instance) {
            if(!$config) {
                throw new RuntimeException("No config defined");
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function createDataBase(array $config) : bool {
        if(empty($config['db'])) {
            throw new RuntimeException("Database is not defined");
        }
        $conn = new mysqli($config['host'], $config['user'], $config['password']);
        $sql = "CREATE DATABASE IF NOT EXISTS $config[db];";
        return $conn->query($sql);
    }

    private function __construct(array $config) {
        foreach(['host', 'db', 'user'] as $item) {
            if(!isset($config[$item])) {
                throw new RuntimeException("$item not defined on database connection config");
            }
        }

        $host = $config['host'];
        $db = $config['db'];
        $user = $config['user'];
        $pass = !empty($config['password']) ? $config['password'] : '';
        $charset = !empty($config['charset']) ? $config['charset'] : 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->pdo = new PDO($dsn, $user, $pass, $opt);
    }

    public function createTable(string $tableName, array $columns) {
        $columns = implode(",\n", $columns);
        $sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
            $columns
        )";

        return $this->pdo->query($sql);
    }

    public function save(string $tableName, array $data) : bool {

        $prepare = [];
        $values = [];

        foreach (array_keys($data) as $key) {
            $prepare[] = "`$key`";
            $values[] = ":$key";
        }
        $prepare = implode(', ', $prepare);
        $values = implode(', ', $values);

        $stmt = $this->pdo->prepare("REPLACE INTO $tableName ($prepare) VALUES ($values)");
        return $stmt->execute($data);
    }

    public function get(string $from, array $args = null) : array {
        if($args) {
            $args = implode(" ", $args);
        }
        return $this->pdo->query("SELECT * FROM $from $args")->fetchAll();
    }

    public function total (string $from) : int {
        return $this->pdo->query("SELECT COUNT(*) FROM $from")->fetchColumn();
    }




}
