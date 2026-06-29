<?php

$dbPath = '../db/database.db';
$dsn = 'sqlite:' . $dbPath;
try {
    $pdo = new PDO($dsn);
    // Throw exceptions for SQL errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Turn on foreign keys (SQLite default is off)
    $pdo->exec('PRAGMA foreign_keys = ON;');
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
