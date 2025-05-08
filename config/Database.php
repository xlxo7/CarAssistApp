<?php
class Database {
    private static ?Database $instance = null;
    private mysqli $conn;

    private string $host = "localhost";
    private string $db_name = "car_assist_db";
    private string $username = "root";
    private string $password = "";

    // Constructor خاص - يمنع الإنشاء المباشر
    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    // منع الاستنساخ Cloning
    private function __clone() {}

    // منع unserialize
    public  function __wakeup() {}

    // Singleton instance
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Getter للاتصال
    public function getConnection(): mysqli {
        return $this->conn;
    }
}
