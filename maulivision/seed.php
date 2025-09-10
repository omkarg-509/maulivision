<?php
// Simple seeder runner
require_once __DIR__ . '/core/Database.php';

$database = new Database();
$db = $database->getConnection();

$seedersDir = __DIR__ . '/database/seeders';
if(!is_dir($seedersDir)) { die("Seeders dir not found" . PHP_EOL); }

$files = glob($seedersDir . '/*.php');
sort($files);
$executed = 0;

foreach($files as $file){
    $seeder = require $file;
    if(!is_object($seeder) || !method_exists($seeder,'run')) { echo "Skipping invalid seeder: $file" . PHP_EOL; continue; }
    try {
        $seeder->run($db);
        echo 'Seeded: ' . basename($file) . PHP_EOL;
        $executed++;
    } catch (Exception $e) {
        echo 'Error seeding ' . basename($file) . ': ' . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}

echo $executed ? ("Completed $executed seeder(s)." . PHP_EOL) : "No seeders executed." . PHP_EOL;
