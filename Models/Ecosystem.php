<?php
class Ecosystem {
  private $connection;
  private $table = 'Ecosystems';

  private $id;
  private $ecosystem;
  private $command;

  public function __construct($db) {
    $this->connection = $db;
  }

  public function getAllEcosystemCommands() {
    $query_command = "SELECT ECOS.command FROM $this->table AS ECOS";

    $stmt_command = $this->connection->prepare($query_command);
    $stmt_command->execute();

    return $stmt_command;
  }
}
?>
