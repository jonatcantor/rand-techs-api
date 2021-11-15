<?php
class Database {
    private $host;
    private $db_name;
    private $db_port;
    private $username;
    private $password;
    private $connection;
    
    public function __construct() {
        $this->host = $_ENV['APP_HOST'];
        $this->db_name = $_ENV['APP_DB_NAME'];
        $this->db_port = $_ENV['APP_DB_PORT'];
        $this->username = $_ENV['APP_DB_USERNAME'];
        $this->password = $_ENV['APP_DB_PASSWORD'];
    }

    public function connect() {
        try {
            $this->connection = new PDO("mysql:host=$this->host; port=$this->db_port; dbname=$this->db_name; sslmode=VERIFY_IDENTITY; sslcert=/etc/pki/tls/certs/ca-bundle.crt; charset=utf8", $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }
        
        return $this->connection;
    }
}
?>