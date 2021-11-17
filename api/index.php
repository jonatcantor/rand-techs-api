<?php
if(!isset($_ENV['APP_PRODUCTION'])) {
  require_once(__DIR__ . '/../vendor/autoload.php');
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . $_ENV['APP_ORIGIN']);
header('Access-Control-Allow-Methods: GET');

$url = parse_url(htmlspecialchars($_SERVER['REQUEST_URI']));
$route = substr($url['path'], 1);

$routes = [
  'Technologies' => '/Technologies.php',
  'Ecosystems' => '/Ecosystems.php'
];

try {
  if(!isset($routes[$route]) || !file_exists(__DIR__ . $routes[$route])) {
    throw new Exception ('error: endpoint not found');
  }

  else {
    require_once(__DIR__ . '/../Config/Database.php');

    $database = new Database();
    $dbConnection = $database->connect();

    require_once(__DIR__ . $routes[$route]);
  }
} catch(Exception $e) {
  echo json_encode(['error' => $e->getMessage()]);
}
?>
