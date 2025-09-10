<?php
// Simple migration runner
require_once __DIR__ . '/core/Database.php';

$database = new Database();
$db = $database->getConnection(); // primary DB

// Ensure migrations table
if(!$db->query("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, filename VARCHAR(255) UNIQUE, migrated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)")) {
    die("Failed ensuring migrations table: " . $db->error . PHP_EOL);
}

// Load applied migrations
$applied = [];
$res = $db->query("SELECT filename FROM migrations");
while($row = $res->fetch_assoc()) { $applied[$row['filename']] = true; }

$migrationsDir = __DIR__ . '/database/migrations';
if(!is_dir($migrationsDir)) { die("Migrations dir not found" . PHP_EOL); }

$files = glob($migrationsDir . '/*.php');
sort($files);
$executed = 0;

foreach($files as $file) {
    $base = basename($file);
    if(isset($applied[$base])) { continue; }
    $migration = require $file;
    if(!is_object($migration) || !method_exists($migration, 'up')) { echo "Skipping invalid migration: $base" . PHP_EOL; continue; }
    try {
        $migration->up($db);
        $stmt = $db->prepare("INSERT INTO migrations (filename) VALUES (?)");
        $stmt->bind_param('s', $base);
        $stmt->execute();
        echo "Migrated: $base" . PHP_EOL;
        $executed++;
    } catch (Exception $e) {
        echo "Error in migration $base: " . $e->getMessage() . PHP_EOL;
        exit(1);
    }
}

echo $executed ? ("Completed $executed new migration(s)." . PHP_EOL) : "No new migrations." . PHP_EOL;
