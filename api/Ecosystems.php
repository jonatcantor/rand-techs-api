<?php
require_once(__DIR__ . '/../Models/Ecosystem.php');

$ecosystem = new Ecosystem($dbConnection);
$ecosystemCommands = $ecosystem->getAllEcosystemCommands()->fetchAll();

if(!empty($ecosystemCommands)) {
  echo json_encode(['ecos' => $ecosystemCommands]);
  return;
}

echo json_encode(['error' => 'error: command not found']);
?>
