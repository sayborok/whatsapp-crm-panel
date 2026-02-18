<?php
// public/index.php
require_once __DIR__ . '/../includes/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['username'], $_POST['password'])) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - WhatsApp CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#0b141a] text-gray-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8 bg-[#1f2c33] p-10 rounded-2xl shadow-2xl border border-gray-700/30">
        <div class="text-center">
            <div
                class="mx-auto h-16 w-16 bg-[#25D366] rounded-full flex items-center justify-center mb-6 shadow-lg shadow-[#25D366]/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight">WhatsApp CRM</h2>
            <p class="mt-2 text-sm text-gray-400">Welcome back! Please enter your details.</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-4 py-3 rounded-xl text-sm text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                    <input id="username" name="username" type="text" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-600 bg-[#2a3942] placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent transition-all sm:text-sm"
                        placeholder="admin">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input id="password" name="password" type="password" required
                        class="appearance-none relative block w-full px-4 py-3 border border-gray-600 bg-[#2a3942] placeholder-gray-500 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[#25D366] focus:border-transparent transition-all sm:text-sm"
                        placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-[#0b141a] bg-[#25D366] hover:bg-[#1db954] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#25D366] transition-all duration-200 transform hover:scale-[1.02]">
                    Sign in to Dashboard
                </button>
            </div>
        </form>
    </div>
</body>

</html>