<?php
// includes/functions.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Powered by sayborok/php-whatsapp-cloud-api
 * GitHub: https://github.com/sayborok/php-whatsapp-cloud-api
 */

// Professional Error Handling
set_exception_handler(function ($e) {
    error_log($e->getMessage());
    header("Location: /error.php?code=500");
    exit;
});

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno))
        return false;

    error_log("[$errno] $errstr in $errfile on line $errline");

    // Only redirect to error page for critical errors
    $fatal_errors = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
    if (in_array($errno, $fatal_errors)) {
        header("Location: /error.php?code=500");
        exit;
    }
    return true; // Don't execute PHP internal error handler
});

use WhatsApp\Config\Config;
use WhatsApp\Http\WhatsAppClient;

/**
 * Get a setting value by key
 */
function getSetting($key)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? (string) $result['setting_value'] : '';
}

/**
 * Save a setting value
 */
function saveSetting($key, $value)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    return $stmt->execute([$key, $value]);
}

/**
 * Get WhatsApp Client Instance
 */
function getWhatsAppClient()
{
    $token = getSetting('wa_access_token');
    $phone_id = getSetting('wa_phone_number_id');

    if (empty($token) || empty($phone_id)) {
        return null;
    }

    $config = new Config($token, $phone_id);
    return new WhatsAppClient($config);
}

/**
 * Get or create contact by phone number
 */
function getOrCreateContact($phone_number, $full_name = null)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM contacts WHERE phone_number = ?");
    $stmt->execute([$phone_number]);
    $contact = $stmt->fetch();

    if (!$contact) {
        $stmt = $pdo->prepare("INSERT INTO contacts (phone_number, full_name) VALUES (?, ?)");
        $stmt->execute([$phone_number, $full_name ?? $phone_number]);
        return $pdo->lastInsertId();
    }

    // Update name only if it's different and not null
    if ($full_name && $contact['full_name'] !== $full_name) {
        $stmt = $pdo->prepare("UPDATE contacts SET full_name = ? WHERE id = ?");
        $stmt->execute([$full_name, $contact['id']]);
    }

    return $contact['id'];
}

/**
 * Log message to database
 */
function logMessage(
    $contact_id,
    $wa_message_id,
    $body,
    $direction,
    $type = 'text',
    $status = 'received',
    $agent_id =
    null
) {
    global $pdo;

    // Check if message ID already exists to avoid duplicates
    if ($wa_message_id) {
        $checkStmt = $pdo->prepare("SELECT id FROM messages WHERE wa_message_id = ?");
        $checkStmt->execute([$wa_message_id]);
        if ($checkStmt->fetch()) {
            return false;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO messages (contact_id, wa_message_id, body, type, direction, status, agent_id)
VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$contact_id, $wa_message_id, $body, $type, $direction, $status, $agent_id]);

    // Update contact last activity
    $stmt = $pdo->prepare("UPDATE contacts SET last_message_at = NOW() WHERE id = ?");
    $stmt->execute([$contact_id]);

    return $pdo->lastInsertId();
}

/**
 * Format timestamp
 */
function formatTime($timestamp)
{
    return date('H:i', strtotime($timestamp));
}