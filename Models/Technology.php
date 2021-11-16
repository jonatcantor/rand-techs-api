<?php
class Technology {
  private $connection;
  private $table = 'Technologies';

  private $id;
  private $name;
  private $type;
  private $ecosystem;
  private $link;

  public function __construct($db) {
    $this->connection = $db;
  }

  public function getRandom($branch) {
    $query_branch = "SELECT T.type AS type_name FROM $this->table AS TECH
                      INNER JOIN Branches AS B ON TECH.id_branches = B.id
                      INNER JOIN Types AS T ON TECH.id_types = T.id
                      WHERE B.branch = :branch
                      ORDER BY RAND()
                      LIMIT 1";
      
    $stmt_branch = $this->connection->prepare($query_branch);
    $stmt_branch->bindParam(':branch', $branch, PDO::PARAM_STR);
    $stmt_branch->execute();

    $type_name = $stmt_branch->fetch()['type_name'];

    $query;
    $where;

    if($type_name == 'Base' || $type_name == 'Preprocessor') {
      $where = 'WHERE T.type = :type_name
                AND B.branch = :branch';
    }

    else if($type_name == 'Dependence') {
      $where = 'WHERE (T.type = :type_name
                AND B.branch = :branch)
                OR (B.branch = :branch
                AND E.ecosystem = TECH.name)';
    }

    else {
      $where = 'WHERE T.type = :type_name
                AND B.branch = :branch
                ORDER BY RAND()
                LIMIT 1';
    }

    $query = "SELECT TECH.name, TECH.link, E.ecosystem AS ecosystem, T.type AS type, B.branch AS branch FROM $this->table AS TECH
              INNER JOIN Ecosystems AS E ON TECH.id_ecosystems = E.id
              INNER JOIN Types AS T ON TECH.id_types = T.id
              INNER JOIN Branches AS B ON TECH.id_branches = B.id
              $where";

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':type_name', $type_name, PDO::PARAM_STR);
    $stmt->bindParam(':branch', $branch, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt;
  }

  public function getFullRandom() {
    $fullRandom = [
      $this->getRandom('Frontend'),
      $this->getRandom('Backend')
    ];

    return $fullRandom;
  }

  public function getEcosystem($ecos) {
    $query = "SELECT TECH.name, TECH.link, E.ecosystem AS ecosystem FROM $this->table AS TECH
              INNER JOIN Ecosystems AS E ON TECH.id_ecosystems = E.id
              WHERE E.ecosystem = :ecos";

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':ecos', $ecos, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt;
  }
}
?>
