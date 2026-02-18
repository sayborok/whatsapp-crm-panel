<?php
// api/get_messages.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

$contact_id = $_GET['contact_id'] ?? null;
if (!$contact_id) {
    echo json_encode([]);
    exit;
}

global $pdo;
$stmt = $pdo->prepare("SELECT * FROM messages WHERE contact_id = ? ORDER BY id ASC");
$stmt->execute([$contact_id]);
echo json_encode($stmt->fetchAll());
