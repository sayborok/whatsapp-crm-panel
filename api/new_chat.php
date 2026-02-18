<?php
// api/new_chat.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

use WhatsApp\Messages\TextMessage;
use WhatsApp\Messages\TemplateMessage;

requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$phone = $data['phone'] ?? '';
$name = $data['name'] ?? '';
$templateName = $data['template'] ?? '';
$language = $data['language'] ?? 'en_US';

if (empty($phone) || empty($templateName)) {
    http_response_code(400);
    exit(json_encode(['error' => 'Phone and template selection are required']));
}

// Clean phone number (remove +, spaces, etc. - keep only digits)
$phone = preg_replace('/[^0-9]/', '', $phone);

try {
    $contact_id = getOrCreateContact($phone, $name ?: $phone);
    $client = getWhatsAppClient();

    if (!$client) {
        throw new Exception("WhatsApp API not configured");
    }

    $wa_message = new TemplateMessage($phone, $templateName, $language);
    $response = $client->sendRequest('/messages', 'POST', $wa_message->toArray());

    $wa_message_id = $response['messages'][0]['id'] ?? null;

    if ($wa_message_id) {
        $body = "[Template: $templateName]";
        logMessage($contact_id, $wa_message_id, $body, 'out', 'text', 'sent', $_SESSION['user_id']);
        echo json_encode(['success' => true, 'contact_id' => $contact_id]);
    } else {
        throw new Exception("Failed to initiate chat");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
