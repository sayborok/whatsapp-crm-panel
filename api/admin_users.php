<?php
// api/admin_users.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdmin();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        $stmt = $pdo->query("SELECT id, username, role, created_at FROM users ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll());
        break;

    case 'save':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty($data['username'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            break;
        }

        if (isset($data['id']) && $data['id']) {
            // Update
            if (!empty($data['password'])) {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
                $stmt->execute([$data['username'], password_hash($data['password'], PASSWORD_DEFAULT), $data['role'], $data['id']]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
                $stmt->execute([$data['username'], $data['role'], $data['id']]);
            }
        } else {
            // Create
            if (empty($data['password'])) {
                echo json_encode(['success' => false, 'error' => 'Password is required for new users']);
                break;
            }
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->execute([$data['username'], password_hash($data['password'], PASSWORD_DEFAULT), $data['role']]);
        }
        echo json_encode(['success' => true]);
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id == $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'error' => 'You cannot delete yourself']);
            break;
        }
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
}
