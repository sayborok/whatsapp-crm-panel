<?php
// public/admin/settings.php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    saveSetting('wa_access_token', $_POST['wa_access_token']);
    saveSetting('wa_phone_number_id', $_POST['wa_phone_number_id']);
    saveSetting('wa_business_account_id', $_POST['wa_business_account_id']);
    saveSetting('wa_verify_token', $_POST['wa_verify_token']);
    $message = 'Settings saved successfully!';
}

$token = getSetting('wa_access_token');
$phone_id = getSetting('wa_phone_number_id');
$waba_id = getSetting('wa_business_account_id');
$verify_token = getSetting('wa_verify_token');
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Settings - WhatsApp CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#0b141a] text-gray-200 p-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-white">API Configuration</h1>
            <a href="../dashboard.php"
                class="text-sm font-medium text-[#25D366] hover:text-[#1db954] flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <?php if ($message): ?>
            <div
                class="bg-green-500/10 border border-green-500/50 text-green-500 px-6 py-4 rounded-xl mb-8 flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>
                    <?= $message ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="bg-[#1f2c33] rounded-2xl p-8 border border-gray-700/30">
            <form action="" method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">WhatsApp Access Token</label>
                    <input type="text" name="wa_access_token" value="<?= htmlspecialchars($token) ?>"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                    <p class="mt-2 text-xs text-gray-500">Long-lived access token from Facebook Developer Panel.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Phone Number ID</label>
                    <input type="text" name="wa_phone_number_id" value="<?= htmlspecialchars($phone_id) ?>"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">WhatsApp Business Account ID (WABA
                        ID)</label>
                    <input type="text" name="wa_business_account_id" value="<?= htmlspecialchars($waba_id) ?>"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                    <p class="mt-2 text-xs text-gray-500">Required for template management.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Webhook Verify Token</label>
                    <input type="text" name="wa_verify_token" value="<?= htmlspecialchars($verify_token) ?>"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                    <p class="mt-2 text-xs text-gray-500">The token you will use when setting up the Webhook in Facebook
                        Console.</p>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-[#25D366] text-[#0b141a] font-bold py-3 rounded-xl hover:bg-[#1db954] transition-all transform hover:scale-[1.01]">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 bg-[#202c33]/50 border border-gray-700/30 rounded-2xl p-6">
            <h3 class="text-white font-semibold mb-2">Webhook URL</h3>
            <p class="text-sm text-gray-400 mb-4">Copy this URL to your Facebook App Webhook configuration:</p>
            <div class="bg-[#0b141a] p-3 rounded-lg text-[#25D366] font-mono text-sm break-all">
                <?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/webhook.php" ?>
            </div>
        </div>
    </div>
</body>

</html>