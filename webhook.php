<?php
// webhook.php

require_once __DIR__ . '/includes/functions.php';
use WhatsApp\Webhooks\WebhookHandler;

$handler = new WebhookHandler();
$verify_token = getSetting('wa_verify_token') ?: 'my_custom_verify_token_123';

// 1. Verify URL (Mandatory for WhatsApp setup)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_mode']) && $_GET['hub_mode'] === 'subscribe') {
    if (isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] === $verify_token) {
        echo $_GET['hub_challenge'];
        exit;
    } else {
        http_response_code(403);
        exit('Verification failed');
    }
}

// 2. Handle incoming notifications
$payload = file_get_contents('php://input');
if (!$payload) {
    exit;
}

$data = json_decode($payload, true);
$processed = $handler->handle($payload);

if ($handler->getType($processed) === 'message') {
    $incoming = $processed['messages'][0];
    $from = $incoming['from'];
    $name = $processed['contacts'][0]['profile']['name'] ?? $from;
    $wa_id = $incoming['id'];
    $type = $incoming['type'];

    $body = '';
    if ($type === 'text') {
        $body = $incoming['text']['body'];
    } elseif ($type === 'image') {
        $body = '[Image Received]';
    } elseif ($type === 'button') {
        $body = $incoming['button']['text'];
    } elseif ($type === 'interactive') {
        $body = $incoming['interactive']['list_reply']['title'] ?? $incoming['interactive']['button_reply']['title'] ?? '[Interactive]';
    } else {
        $body = '[' . ucfirst($type) . ' Message]';
    }

    $contact_id = getOrCreateContact($from, $name);
    logMessage($contact_id, $wa_id, $body, 'in', $type, 'received');

} elseif ($handler->getType($processed) === 'status') {
    $status_data = $processed['statuses'][0];
    $wa_id = $status_data['id'];
    $status = $status_data['status']; // sent, delivered, read, failed

    global $pdo;
    $stmt = $pdo->prepare("UPDATE messages SET status = ? WHERE wa_message_id = ?");
    $stmt->execute([$status, $wa_id]);
}

http_response_code(200);
echo 'OK';
