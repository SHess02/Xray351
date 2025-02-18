<?php
class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $dbname = "alumniconnectdb";
    public $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname);
        $this->conn->set_charset("utf8");

        if ($this->conn->connect_errno) {
            die('<h3>Database Access Error!</h3>' . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function escape($value) {
        return $this->conn->real_escape_string($value);
    }

    public function close() {
        $this->conn->close();
    }
}
?>