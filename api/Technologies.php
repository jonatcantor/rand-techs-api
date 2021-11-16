<?php
if(!isset($_ENV['APP_PRODUCTION'])) {
  require_once(__DIR__ . '/../vendor/autoload.php');
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

require_once(__DIR__ . '/../Config/Database.php');
require_once(__DIR__ . '/../Models/Technology.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . $_ENV['APP_ORIGIN']);
header('Access-Control-Allow-Methods: GET');

$database = new Database();
$dbConnection = $database->connect();

$technology = new Technology($dbConnection);

if(!isset($_GET['command']) || $_GET['command'] == '') {
  echo json_encode(['error' => 'error: command not found']);
  return;
}

$command = htmlspecialchars($_GET['command']);
$commandSplit = explode(' ', $command);

if(count($commandSplit) != 2) {
  echo json_encode(['error' => 'error: command not found']);
  return;
}

if($commandSplit[0] == 'rand') {
  if($commandSplit[1] == 'full') {
    $techElements = $technology->getFullRandom();
    $fronElements = $techElements[0]->fetchAll();
    $backElements = $techElements[1]->fetchAll();

    if(!empty($fronElements) && !empty($backElements)) {
      echo json_encode(['fron' => $fronElements, 'back' => $backElements]);
    }

    else {
      echo json_encode(['error' => 'error: command not found']);
    }
  }

  else {
    $techElements = $technology->getRandom($commandSplit[1])->fetchAll();

    if(!empty($techElements)) {
      echo json_encode([$commandSplit[1] => $techElements]);
    }

    else {
      echo json_encode(['error' => 'error: command not found']);
    }
  }

  return;
}

if($commandSplit[0] == 'ecos') {
  $techElements = $technology->getEcosystem($commandSplit[1])->fetchAll();

  if(!empty($techElements)) { 
    echo json_encode([$commandSplit[1] => $techElements]);
  }

  else {
    echo json_encode(['error' => 'error: command not found']);
  }

  return;
}

echo json_encode(['error' => 'error: command not found']);
?>
