<?php
// includes/auth.php

session_start();
require_once __DIR__ . '/../config/database.php';

function login($username, $password)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function logout()
{
    session_destroy();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: /index.php");
        exit;
    }
}

function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
        header("Location: /dashboard.php?error=unauthorized");
        exit;
    }
}
