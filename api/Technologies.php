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
    define ('COMMANDS', [
        'fron' => 'Frontend',
        'back' => 'Backend',
        'full' => 'FullStack'
    ]);

    if(!array_key_exists($commandSplit[1], COMMANDS)) {
        echo json_encode(['error' => 'error: command not found']);
    }

    else if($commandSplit[1] == 'full') {
        $techElements = $technology->getFullRandom();
        echo json_encode(['fron' => $techElements[0]->fetchAll(), 'back' => $techElements[1]->fetchAll()]);
    }


    else {
        $techElements = $technology->getRandom(COMMANDS[$commandSplit[1]]);
        echo json_encode([$commandSplit[1] => $techElements->fetchAll()]);
    }

    return;
}

if($commandSplit[0] == 'ecos') {
    define ('COMMANDS', [
        'react' => 'React'
    ]);

    if(!array_key_exists($commandSplit[1], COMMANDS)) {
        echo json_encode(['error' => 'error: command not found']);
    }

    else {
        $techElements = $technology->getEcosystem($commandSplit[1]);
        echo json_encode(['ecos' => $techElements->fetchAll()]);
    }

    return;
}

echo json_encode(['error' => 'error: command not found']);
?>
