<?php
// api/admin_templates.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

$token = getSetting('wa_access_token');
$waba_id = getSetting('wa_business_account_id');

if (empty($token) || empty($waba_id)) {
    http_response_code(400);
    exit(json_encode(['error' => 'WhatsApp API (Token/WABA ID) not configured']));
}

$action = $_GET['action'] ?? '';

function callWAPlateAPI($endpoint, $method = 'GET', $data = null)
{
    global $token;
    $url = "https://graph.facebook.com/v18.0/$endpoint";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

switch ($action) {
    case 'list':
        $res = callWAPlateAPI("$waba_id/message_templates");
        echo json_encode($res['data']['data'] ?? []);
        break;

    case 'delete':
        $name = $_GET['name'] ?? null;
        if (!$name) {
            echo json_encode(['success' => false, 'error' => 'Template name required']);
            break;
        }
        $res = callWAPlateAPI("$waba_id/message_templates?name=$name", 'DELETE');
        if ($res['code'] === 200 || (isset($res['data']['success']) && $res['data']['success'])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $res['data']['error']['message'] ?? 'Delete failed']);
        }
        break;

    case 'create':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            break;
        }

        // Prepare template data for FB API
        $template = [
            'name' => $data['name'],
            'language' => $data['language'],
            'category' => $data['category'],
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $data['body']
                ]
            ]
        ];

        $res = callWAPlateAPI("$waba_id/message_templates", 'POST', $template);
        if ($res['code'] === 200 || (isset($res['data']['id']))) {
            echo json_encode(['success' => true, 'id' => $res['data']['id']]);
        } else {
            echo json_encode(['success' => false, 'error' => $res['data']['error']['message'] ?? 'Creation failed']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
}
