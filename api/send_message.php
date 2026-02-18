<?php
// api/send_message.php

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$contact_id = $_POST['contact_id'] ?? null;
$message_body = $_POST['message'] ?? '';
$type = $_POST['type'] ?? 'text';

if (!$contact_id || empty($message_body)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Missing required fields']));
}

global $pdo;
$stmt = $pdo->prepare("SELECT phone_number FROM contacts WHERE id = ?");
$stmt->execute([$contact_id]);
$contact = $stmt->fetch();

if (!$contact) {
    http_response_code(404);
    exit(json_encode(['error' => 'Contact not found']));
}

$client = getWhatsAppClient();
if (!$client) {
    http_response_code(500);
    exit(json_encode(['error' => 'WhatsApp API not configured']));
}

use WhatsApp\Messages\TextMessage;

try {
    $wa_message = new TextMessage($contact['phone_number'], $message_body);
    $response = $client->sendRequest('/messages', 'POST', $wa_message->toArray());

    $wa_message_id = $response['messages'][0]['id'] ?? null;

    if ($wa_message_id) {
        logMessage($contact_id, $wa_message_id, $message_body, 'out', 'text', 'sent', $_SESSION['user_id']);
        echo json_encode(['success' => true, 'message_id' => $wa_message_id]);
    } else {
        throw new Exception("Failed to get message ID from WhatsApp response");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
