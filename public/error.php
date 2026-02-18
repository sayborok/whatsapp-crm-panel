<?php
// public/error.php
$status = $_GET['code'] ?? 500;
$messages = [
    403 => "Access Denied. You don't have permission to view this page.",
    404 => "Page Not Found. The resource you're looking for doesn't exist.",
    500 => "Internal Server Error. Something went wrong on our end.",
    'db' => "Database Connection Failed. Please check your configuration."
];
$msg = $messages[$status] ?? "An unexpected error occurred.";
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error
        <?= htmlspecialchars($status) ?> - WhatsApp CRM
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#0b141a] text-gray-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6">
        <div
            class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-500/10 border border-red-500/20 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 1.5.3c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h1 class="text-6xl font-black text-white">
            <?= htmlspecialchars($status) ?>
        </h1>
        <h2 class="text-2xl font-bold text-gray-300">Oops! Something went wrong.</h2>
        <p class="text-gray-400">
            <?= htmlspecialchars($msg) ?>
        </p>
        <div class="pt-6">
            <a href="index.php"
                class="inline-flex items-center space-x-2 bg-[#25D366] text-[#0b141a] font-bold py-3 px-8 rounded-xl hover:bg-[#1db954] transition-all transform hover:scale-[1.05]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
</body>

</html>