<?php
// api/get_contacts.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

global $pdo;
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY last_message_at DESC");
echo json_encode($stmt->fetchAll());
