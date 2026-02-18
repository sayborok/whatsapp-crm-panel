<?php
// public/admin/users.php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - WhatsApp CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#0b141a] text-gray-200" x-data="userManager()">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div class="w-64 bg-[#111b21] border-r border-gray-700/30 flex flex-col">
            <div class="p-6">
                <h1 class="text-xl font-bold text-[#25D366]">WhatsApp CRM</h1>
                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">Admin Panel</p>
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="../dashboard.php"
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-[#202c33] transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Chats</span>
                </a>
                <a href="users.php" class="flex items-center space-x-3 p-3 rounded-xl bg-[#202c33] text-[#25D366]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>
                <a href="templates.php"
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-[#202c33] transition-colors text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Templates</span>
                </a>
                <a href="settings.php"
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-[#202c33] transition-colors text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="p-4 border-t border-gray-700/30">
                <a href="../logout.php"
                    class="flex items-center space-x-3 p-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-[#0b141a]">
            <!-- Header -->
            <header class="bg-[#202c33] px-8 py-4 flex items-center justify-between border-b border-gray-700/30">
                <h2 class="text-xl font-semibold text-white">User Management</h2>
                <button @click="openModal()"
                    class="bg-[#25D366] text-[#0b141a] px-6 py-2 rounded-xl font-bold hover:bg-[#1db954] transition-all transform hover:scale-[1.02]">
                    Add New User
                </button>
            </header>

            <!-- Table -->
            <main class="flex-1 p-8 overflow-y-auto">
                <div class="bg-[#1f2c33] rounded-2xl border border-gray-700/30 overflow-hidden shadow-xl">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#2a3942] text-gray-300 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Username</th>
                                <th class="px-6 py-4 font-semibold">Role</th>
                                <th class="px-6 py-4 font-semibold">Created At</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/30">
                            <template x-for="user in users" :key="user.id">
                                <tr class="hover:bg-[#202c33] transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="text-white font-medium" x-text="user.username"></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            :class="user.role === 'admin' ? 'bg-purple-500/10 text-purple-400' : 'bg-blue-500/10 text-blue-400'"
                                            class="px-3 py-1 rounded-full text-xs font-bold uppercase"
                                            x-text="user.role"></span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-sm"
                                        x-text="new Date(user.created_at).toLocaleDateString()"></td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button @click="editUser(user)"
                                            class="text-[#25D366] hover:bg-[#25D366]/10 p-2 rounded-lg transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteUser(user.id)"
                                            class="text-red-500 hover:bg-red-500/10 p-2 rounded-lg transition-colors"
                                            x-show="user.id != <?= $_SESSION['user_id'] ?>">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#0b141a]/80 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="bg-[#1f2c33] w-full max-w-md rounded-2xl shadow-2xl border border-gray-700/30 relative z-10 overflow-hidden transform transition-all"
            x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <div class="px-8 py-6 border-b border-gray-700/30 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white" x-text="form.id ? 'Edit User' : 'Add New User'"></h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form @submit.prevent="saveUser()" class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                    <input type="text" x-model="form.username" required
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#25D366] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2"
                        x-text="form.id ? 'New Password (Leave blank to keep current)' : 'Password'"></label>
                    <input type="password" x-model="form.password" :required="!form.id"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#25D366] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Role</label>
                    <select x-model="form.role"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[#25D366] transition-all">
                        <option value="agent">Agent</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="pt-4 flex space-x-4">
                    <button type="button" @click="closeModal()"
                        class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 font-medium hover:bg-[#2a3942] transition-colors">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#25D366] text-[#0b141a] rounded-xl font-bold hover:bg-[#1db954] transition-colors">Save
                        User</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function userManager() {
            return {
                users: [],
                showModal: false,
                form: { id: null, username: '', password: '', role: 'agent' },

                init() {
                    this.fetchUsers();
                },

                async fetchUsers() {
                    const res = await fetch('../../api/admin_users.php?action=list');
                    this.users = await res.json();
                },

                openModal() {
                    this.form = { id: null, username: '', password: '', role: 'agent' };
                    this.showModal = true;
                },

                editUser(user) {
                    this.form = { id: user.id, username: user.username, password: '', role: user.role };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },

                async saveUser() {
                    const res = await fetch('../../api/admin_users.php?action=save', {
                        method: 'POST',
                        body: JSON.stringify(this.form)
                    });
                    const result = await res.json();
                    if (result.success) {
                        this.fetchUsers();
                        this.closeModal();
                    } else {
                        alert(result.error);
                    }
                },

                async deleteUser(id) {
                    if (!confirm('Are you sure you want to delete this user?')) return;
                    const res = await fetch(`../../api/admin_users.php?action=delete&id=${id}`);
                    const result = await res.json();
                    if (result.success) {
                        this.fetchUsers();
                    } else {
                        alert(result.error);
                    }
                }
            }
        }
    </script>
</body>

</html>