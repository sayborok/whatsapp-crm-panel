<?php
// public/admin/templates.php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Management - WhatsApp CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-[#0b141a] text-gray-200" x-data="templateManager()">
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
                <a href="users.php"
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-[#202c33] transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>
                <a href="templates.php" class="flex items-center space-x-3 p-3 rounded-xl bg-[#202c33] text-[#25D366]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Templates</span>
                </a>
                <a href="settings.php"
                    class="flex items-center space-x-3 p-3 rounded-xl hover:bg-[#202c33] transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-[#0b141a]">
            <header class="bg-[#202c33] px-8 py-4 flex items-center justify-between border-b border-gray-700/30">
                <h2 class="text-xl font-semibold text-white">Message Templates</h2>
                <button @click="openModal()"
                    class="bg-[#25D366] text-[#0b141a] px-6 py-2 rounded-xl font-bold hover:bg-[#1db954] transition-all">
                    Create Template
                </button>
            </header>

            <main class="flex-1 p-8 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="tpl in templates" :key="tpl.id">
                        <div class="bg-[#1f2c33] rounded-2xl border border-gray-700/30 p-6 flex flex-col shadow-lg">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-white font-bold text-lg" x-text="tpl.name"></h3>
                                    <span class="text-xs text-gray-500 uppercase tracking-widest"
                                        x-text="tpl.category"></span>
                                </div>
                                <span :class="{
                                    'bg-green-500/10 text-green-400': tpl.status === 'APPROVED',
                                    'bg-yellow-500/10 text-yellow-400': tpl.status === 'PENDING',
                                    'bg-red-500/10 text-red-400': tpl.status === 'REJECTED'
                                }" class="px-2 py-1 rounded text-[10px] font-bold" x-text="tpl.status"></span>
                            </div>
                            <div
                                class="flex-1 bg-[#2a3942] rounded-xl p-4 mb-4 text-sm text-gray-300 overflow-hidden line-clamp-4">
                                <template x-for="comp in tpl.components" :key="comp.type">
                                    <p x-show="comp.type === 'BODY'" x-text="comp.text"></p>
                                </template>
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span x-text="tpl.language"></span>
                                <button @click="deleteTemplate(tpl.name)"
                                    class="text-red-500 hover:text-red-400 font-bold uppercase tracking-wider">Delete</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="templates.length === 0" class="text-center py-20 text-gray-500">
                    <p>No templates found. Create one to get started!</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#0b141a]/80 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="bg-[#1f2c33] w-full max-w-lg rounded-2xl shadow-2xl border border-gray-700/30 relative z-10"
            x-show="showModal" x-transition>
            <div class="px-8 py-6 border-b border-gray-700/30 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Create New Template</h3>
                <button @click="closeModal()" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form @submit.prevent="createTemplate()" class="p-8 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                        <input type="text" x-model="form.name" placeholder="lowercase_and_underscores" required
                            class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Language</label>
                        <select x-model="form.language"
                            class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                            <option value="en_US">English (US)</option>
                            <option value="tr">Turkish</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Category</label>
                    <select x-model="form.category"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                        <option value="MARKETING">Marketing</option>
                        <option value="UTILITY">Utility</option>
                        <option value="AUTHENTICATION">Authentication</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Body Text</label>
                    <textarea x-model="form.body" rows="4" required placeholder="Hello {{1}}, welcome to our service!"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none resize-none"></textarea>
                </div>
                <div class="pt-4 flex space-x-4">
                    <button type="button" @click="closeModal()"
                        class="flex-1 px-4 py-3 border border-gray-600 rounded-xl text-gray-300 font-medium hover:bg-[#2a3942] transition-colors">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#25D366] text-[#0b141a] font-bold rounded-xl hover:bg-[#1db954] transition-all">Create</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function templateManager() {
            return {
                templates: [],
                showModal: false,
                form: { name: '', language: 'en_US', category: 'MARKETING', body: '' },

                init() {
                    this.fetchTemplates();
                },

                async fetchTemplates() {
                    try {
                        const res = await fetch(`../../api/admin_templates.php?action=list`);
                        this.templates = await res.json();
                        if (this.templates.error) {
                            alert(this.templates.error);
                            this.templates = [];
                        }
                    } catch (e) {
                        console.error(e);
                    }
                },

                openModal() {
                    this.form = { name: '', language: 'en_US', category: 'MARKETING', body: '' };
                    this.showModal = true;
                },

                closeModal() {
                    this.showModal = false;
                },

                async createTemplate() {
                    const res = await fetch(`../../api/admin_templates.php?action=create`, {
                        method: 'POST',
                        body: JSON.stringify(this.form)
                    });
                    const result = await res.json();
                    if (result.success) {
                        this.fetchTemplates();
                        this.closeModal();
                    } else {
                        alert(result.error);
                    }
                },

                async deleteTemplate(name) {
                    if (!confirm('Are you sure you want to delete this template?')) return;
                    const res = await fetch(`../../api/admin_templates.php?action=delete&name=${name}`);
                    const result = await res.json();
                    if (result.success) {
                        this.fetchTemplates();
                    } else {
                        alert(result.error);
                    }
                }
            }
        }
    </script>
</body>

</html>