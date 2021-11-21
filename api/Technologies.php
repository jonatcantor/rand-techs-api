<?php
require_once(__DIR__ . '/../Models/Technology.php');

$technology = new Technology($dbConnection);

if(!isset($_GET['command']) || $_GET['command'] == '') {
  http_response_code(404);
  echo json_encode(['error' => 'error: command not found']);
  return;
}

$command = htmlspecialchars($_GET['command']);
$commandSplit = explode(' ', $command);

if(count($commandSplit) != 2) {
  http_response_code(404);
  echo json_encode(['error' => 'error: command not found']);
  return;
}

if(preg_match('/^[A-Z].*$/', $commandSplit[0]) || preg_match('/^[A-Z].*$/', $commandSplit[1])) {
  http_response_code(404);
  echo json_encode(['error' => 'error: command not found']);
  return;
}

if($commandSplit[0] == 'rand') {
  if($commandSplit[1] == 'full') {
    $techElements = $technology->getFullRandom();
    $fronElements = $techElements[0]->fetchAll();
    $backElements = $techElements[1]->fetchAll();

    if(!empty($fronElements) && !empty($backElements)) {
      http_response_code(200);
      echo json_encode(['fron' => $fronElements, 'back' => $backElements]);
    }

    else {
      http_response_code(404);
      echo json_encode(['error' => 'error: command not found']);
    }
  }

  else {
    $techElements = $technology->getRandom($commandSplit[1])->fetchAll();

    if(!empty($techElements)) {
      http_response_code(200);
      echo json_encode([$commandSplit[1] => $techElements]);
    }

    else {
      http_response_code(404);
      echo json_encode(['error' => 'error: command not found']);
    }
  }

  return;
}

if($commandSplit[0] == 'ecos') {
  $techElements = $technology->getEcosystem($commandSplit[1])->fetchAll();

  if(!empty($techElements)) {
    http_response_code(200);
    echo json_encode(['ecos' => $techElements]);
  }

  else {
    http_response_code(404);
    echo json_encode(['error' => 'error: command not found']);
  }

  return;
}

http_response_code(404);
echo json_encode(['error' => 'error: command not found']);
?>
