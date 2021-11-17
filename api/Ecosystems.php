<?php
if(!isset($_ENV['APP_PRODUCTION'])) {
  require_once(__DIR__ . '/../vendor/autoload.php');
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

require_once(__DIR__ . '/../Config/Database.php');
require_once(__DIR__ . '/../Models/Ecosystem.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . $_ENV['APP_ORIGIN']);
header('Access-Control-Allow-Methods: GET');

$database = new Database();
$dbConnection = $database->connect();

$ecosystem = new Ecosystem($dbConnection);
$ecosystemCommands = $ecosystem->getAllEcosystemCommands()->fetchAll();

if(!empty($ecosystemCommands)) {
  echo json_encode(['ecos' => $ecosystemCommands]);
  return;
}

echo json_encode(['error' => 'error: command not found']);
?>
