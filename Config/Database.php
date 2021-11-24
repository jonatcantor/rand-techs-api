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
    $options = [
      PDO::MYSQL_ATTR_SSL_CA => '/etc/pki/tls/certs/ca-bundle.crt',
      PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];

    if(!isset($_ENV['APP_PRODUCTION'])) $options = [];

    try {
      $this->connection = new PDO("mysql:host=$this->host; port=$this->db_port; dbname=$this->db_name; charset=utf8", $this->username, $this->password, $options);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
      http_response_code(500);
      echo json_encode(['error' => 'Connection error: ' . $e->getMessage()]);
    }
    
    return $this->connection;
  }
}
?>
