<?php
require_once(__DIR__ . '/../Models/Ecosystem.php');

$ecosystem = new Ecosystem($dbConnection);
$ecosystemCommands = $ecosystem->getAllEcosystemCommands()->fetchAll();

if(!empty($ecosystemCommands)) {
  http_response_code(200);
  echo json_encode(['ecos' => $ecosystemCommands]);
  return;
}

http_response_code(404);
echo json_encode(['error' => 'error: command not found']);
?>
