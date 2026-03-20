<?php

declare(strict_types=1);

$databasesDirectory = dirname(__DIR__) . '/database/';

$databaseSchemaFile = $databasesDirectory . 'schema.sql';
if (!file_exists($databaseSchemaFile)) {
    throw new RuntimeException('Database schema file does not exist');
}

$databaseSeedFile = $databasesDirectory . 'seed.sql';
if (!file_exists($databaseSeedFile)) {
    throw new RuntimeException('Database seed file does not exist');
}

$databaseFile = $databasesDirectory . 'database.sqlite';
if (file_exists($databaseFile)) {
    unlink($databaseFile);
}

if(!touch($databaseFile)){
    throw new RuntimeException('Unable to create datatable file');
}


$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$schema = file_get_contents($databaseSchemaFile);
$seed = file_get_contents($databaseSeedFile);

$pdo->exec($schema);
$pdo->exec($seed);

echo "Database initialized successfully." . PHP_EOL;