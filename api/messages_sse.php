<?php
// api/messages_sse.php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    echo "data: " . json_encode(['error' => 'Unauthorized']) . "\n\n";
    exit;
}

$last_id = isset($_GET['last_id']) ? (int) $_GET['last_id'] : 0;
$contact_id = isset($_GET['contact_id']) ? (int) $_GET['contact_id'] : null;

// Infinite loop for SSE
while (true) {
    global $pdo;

    $sql = "SELECT m.*, c.full_name as contact_name 
            FROM messages m 
            JOIN contacts c ON m.contact_id = c.id 
            WHERE m.id > ?";
    $params = [$last_id];

    if ($contact_id) {
        $sql .= " AND m.contact_id = ?";
        $params[] = $contact_id;
    }

    $sql .= " ORDER BY m.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $new_messages = $stmt->fetchAll();

    if (!empty($new_messages)) {
        foreach ($new_messages as $msg) {
            echo "data: " . json_encode($msg) . "\n\n";
            $last_id = $msg['id'];
        }
    }

    // Check for status updates as well? 
    // For simplicity in this step, we mainly push new messages.

    if (ob_get_level() > 0)
        ob_flush();
    flush();

    if (connection_aborted())
        break;

    sleep(2); // Wait for 2 seconds before checking again
}
