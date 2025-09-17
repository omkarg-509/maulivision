<?php
// Simple Router for LMS
session_start();

require_once __DIR__.'/config/config.php';
require_once __DIR__.'/core/Database.php';
require_once __DIR__.'/core/Controller.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if (strpos($path, $base) === 0) $path = substr($path, strlen($base));
$path = trim($path, '/');

function route($method, $pattern, $callback){
  static $routes = [];
  if ($callback !== null) { $routes[] = [$method, "#^".$pattern."$#", $callback]; return; }
  foreach($routes as [$m,$p,$cb]){ if($m === $_SERVER['REQUEST_METHOD'] && preg_match($p, $_SERVER['REQUEST_URI'], $matches)){ array_shift($matches); return $cb(...$matches);} }
  http_response_code(404); echo 'Not Found';
}

// Controllers require
require_once __DIR__.'/controllers/CustomerController.php';
require_once __DIR__.'/controllers/BillController.php';

// Routes
route('GET','/lms/?', function(){ (new CustomerController())->index(); });
route('POST','/lms/customer/store', function(){ (new CustomerController())->store(); });
route('GET','/lms/bill/create/(\d+)', function($cid){ (new BillController())->create($cid); });
route('POST','/lms/bill/store', function(){ (new BillController())->store(); });
route('GET','/lms/bill/show/(\d+)', function($bid){ (new BillController())->show($bid); });
route('GET','/lms/bill/list', function(){ (new BillController())->list(); });

// Dispatch
route(null,null,null);
