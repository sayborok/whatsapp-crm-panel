<?php
// public/dashboard.php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireLogin();

// Fetch initial contact list
global $pdo;
$stmt = $pdo->query("SELECT * FROM contacts ORDER BY last_message_at DESC");
$contacts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - WhatsApp CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }

        .message-in {
            @apply bg-[#202c33] text-gray-200 rounded-tr-lg rounded-br-lg rounded-bl-lg;
        }

        .message-out {
            @apply bg-[#005c4b] text-white rounded-tl-lg rounded-bl-lg rounded-br-lg;
        }
    </style>
</head>

<body class="h-full bg-[#111b21] text-gray-200 overflow-hidden" x-data="chatApp()">

    <div class="flex h-full overflow-hidden">
        <!-- Left Panel: Contacts -->
        <div class="w-1/4 min-w-[300px] border-r border-gray-700/50 flex flex-col bg-[#111b21]">
            <header class="h-[60px] bg-[#202c33] flex items-center justify-between px-4 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-bold text-lg">
                        <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                    </div>
                    <span class="font-medium">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>
                </div>
                <div class="flex items-center space-x-4 text-gray-400">
                    <button @click="openNewChatModal()" title="New Chat" class="hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <?php if (isAdmin()): ?>
                        <a href="admin/users.php" title="Manage Users">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </a>
                        <a href="admin/settings.php" title="Settings">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </header>

            <div class="px-4 py-2 border-b border-gray-700/50">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="text" x-model="search"
                        class="w-full bg-[#202c33] text-sm text-white rounded-lg pl-10 pr-4 py-2 focus:outline-none"
                        placeholder="Search or start new chat">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                <template x-for="contact in filteredContacts" :key="contact.id">
                    <div class="flex items-center px-4 py-3 cursor-pointer hover:bg-[#202c33] transition-colors border-b border-gray-700/30"
                        :class="activeContact && activeContact.id == contact.id ? 'bg-[#2a3942]' : ''"
                        @click="setActiveContact(contact)">
                        <div
                            class="w-12 h-12 rounded-full bg-gray-600 flex items-center justify-center shrink-0 text-white font-bold mr-3 shadow-inner">
                            <span x-text="contact.full_name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-medium text-gray-100 truncate" x-text="contact.full_name"></h3>
                                <span class="text-xs text-gray-500" x-text="formatDate(contact.last_message_at)"></span>
                            </div>
                            <p class="text-sm text-gray-400 truncate" x-text="contact.last_body || 'No messages yet'">
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Middle Panel: Chat Window -->
        <div class="flex-1 flex flex-col bg-[#0b141a] relative" x-show="activeContact">
            <!-- Chat Header -->
            <header class="h-[60px] bg-[#202c33] flex items-center justify-between px-4 shrink-0 shadow-md z-10">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-bold text-white shadow-inner">
                        <span x-text="activeContact.full_name.charAt(0).toUpperCase()"></span>
                    </div>
                    <div>
                        <h2 class="font-medium text-gray-100" x-text="activeContact.full_name"></h2>
                        <span class="text-xs text-[#25D366]" x-text="activeContact.phone_number"></span>
                    </div>
                </div>
                <div class="flex space-x-4 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer hover:text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer hover:text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </div>
            </header>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-[url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png')] bg-repeat bg-opacity-5"
                x-ref="messageList">
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex" :class="msg.direction === 'out' ? 'justify-end' : 'justify-start'">
                        <div class="max-w-[65%] px-3 py-1.5 shadow-sm relative"
                            :class="msg.direction === 'out' ? 'message-out' : 'message-in'">
                            <p class="text-sm whitespace-pre-wrap" x-text="msg.body"></p>
                            <div class="flex items-center justify-end space-x-1 mt-1">
                                <span class="text-[10px] opacity-60" x-text="formatTime(msg.created_at)"></span>
                                <template x-if="msg.direction === 'out'">
                                    <span class="text-[10px]">
                                        <svg x-show="msg.status === 'sent'" class="h-3 w-3 text-gray-400"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" />
                                        </svg>
                                        <svg x-show="msg.status === 'delivered'" class="h-3 w-3 text-gray-400"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41z" />
                                        </svg>
                                        <svg x-show="msg.status === 'read'" class="h-3 w-3 text-[#53bdeb]"
                                            fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M18 7l-1.41-1.41-6.34 6.34 1.41 1.41L18 7zm4.24-1.41L11.66 16.17 7.48 12l-1.41 1.41L11.66 19l12-12-1.42-1.41z" />
                                        </svg>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Input Area -->
            <form @submit.prevent="sendMessage" class="h-[62px] bg-[#202c33] flex items-center px-4 space-x-4 shrink-0">
                <button type="button" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
                <button type="button" class="text-gray-400 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <div class="flex-1">
                    <input type="text" x-model="newMessage"
                        class="w-full bg-[#2a3942] text-white border-none rounded-lg px-4 py-2 focus:ring-0 placeholder-gray-500"
                        placeholder="Type a message">
                </div>
                <button type="submit" class="text-[#25D366] hover:text-[#1db954]" :disabled="!newMessage.trim()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-90" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Empty State -->
        <div class="flex-1 flex flex-col items-center justify-center bg-[#222e35] border-b-[6px] border-[#25D366]"
            x-show="!activeContact">
            <div class="w-64 h-64 bg-no-repeat bg-contain opacity-20 mb-8"
                style="background-image: url('https://static.whatsapp.net/rsrc.php/v3/y6/r/wa69_n_IDvP.png')"></div>
            <h1 class="text-3xl font-light text-gray-300">WhatsApp Web</h1>
            <p class="text-gray-500 mt-4 text-sm text-center max-w-sm">Select a contact to start messaging. Your
                messages will be stored securely in the CRM.</p>
        </div>

        <!-- Right Panel: Information -->
        <div class="w-[30%] max-w-[400px] border-l border-gray-700/50 bg-[#111b21] flex flex-col"
            x-show="activeContact">
            <header class="h-[60px] bg-[#202c33] flex items-center px-6 border-l border-gray-700/50">
                <span class="font-medium">Contact Info</span>
            </header>
            <div class="flex-1 overflow-y-auto">
                <div class="flex flex-col items-center py-8 bg-[#111b21]">
                    <div
                        class="w-48 h-48 rounded-full bg-gray-600 flex items-center justify-center text-6xl text-white font-bold mb-4 shadow-xl">
                        <span x-text="activeContact.full_name.charAt(0).toUpperCase()"></span>
                    </div>
                    <h2 class="text-2xl font-medium" x-text="activeContact.full_name"></h2>
                    <p class="text-gray-400" x-text="activeContact.phone_number"></p>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">About</h4>
                        <p class="text-sm text-gray-300">Customer CRM Profile</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Settings</h4>
                        <button
                            class="w-full text-left py-2 text-sm text-red-400 hover:text-red-300 flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                            <span>Block Contact</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                contacts: <?= json_encode($contacts) ?>,
                search: '',
                showNewChatModal: false,
                templates: [],
                newChat: { phone: '', name: '', template: '', language: '' },
                activeContact: null,
                messages: [],
                newMessage: '',
                lastMessageId: 0,
                sse: null,

                init() {
                    // Initial setup if needed
                },

                get filteredContacts() {
                    return this.contacts
                        .filter(c =>
                            c.full_name.toLowerCase().includes(this.search.toLowerCase()) ||
                            c.phone_number.includes(this.search)
                        )
                        .sort((a, b) => new Date(b.last_message_at) - new Date(a.last_message_at));
                },

                async setActiveContact(contact) {
                    this.activeContact = contact;
                    this.messages = [];
                    this.lastMessageId = 0;

                    // Fetch message history
                    const response = await fetch(`api/get_messages.php?contact_id=${contact.id}`);
                    const data = await response.json();
                    this.messages = data;
                    if (data.length > 0) {
                        this.lastMessageId = data[data.length - 1].id;
                    }

                    this.scrollToBottom();
                    this.initSSE();
                },

                initSSE() {
                    if (this.sse) this.sse.close();

                    this.sse = new EventSource(`api/messages_sse.php?contact_id=${this.activeContact.id}&last_id=${this.lastMessageId}`);

                    this.sse.onmessage = (event) => {
                        const msg = JSON.parse(event.data);
                        if (!this.messages.find(m => m.id === msg.id)) {
                            this.messages.push(msg);
                            this.lastMessageId = msg.id;
                            this.scrollToBottom();
                            // Update contact list last body and timestamp
                            const cIdx = this.contacts.findIndex(c => c.id == msg.contact_id);
                            if (cIdx !== -1) {
                                this.contacts[cIdx].last_body = msg.body;
                                this.contacts[cIdx].last_message_at = msg.created_at;
                            } else {
                                // New contact! Fetch list again or add manually
                                this.fetchContacts();
                            }
                        }
                    };
                },

                async fetchContacts() {
                    const response = await fetch('api/get_contacts.php');
                    this.contacts = await response.json();
                },

                async openNewChatModal() {
                    this.newChat = { phone: '', name: '', template: '', language: '' };
                    this.showNewChatModal = true;
                    // Fetch approved templates
                    const res = await fetch('api/admin_templates.php?action=list');
                    const allTpls = await res.json();
                    this.templates = allTpls.filter(t => t.status === 'APPROVED');
                },

                getSelectedTemplateBody() {
                    const tpl = this.templates.find(t => t.name === this.newChat.template);
                    if (!tpl) return '';
                    const bodyComp = tpl.components.find(c => c.type === 'BODY');
                    return bodyComp ? bodyComp.text : '';
                },

                async sendNewChat() {
                    if (!this.newChat.phone || !this.newChat.template) return;
                    const res = await fetch('api/new_chat.php', {
                        method: 'POST',
                        body: JSON.stringify(this.newChat)
                    });
                    const result = await res.json();
                    if (result.success) {
                        this.showNewChatModal = false;
                        await this.fetchContacts();
                        const contact = this.contacts.find(c => c.id == result.contact_id);
                        if (contact) this.setActiveContact(contact);
                    } else {
                        alert(result.error);
                    }
                },

                async sendMessage() {
                    if (!this.newMessage.trim() || !this.activeContact) return;

                    const msgText = this.newMessage;
                    this.newMessage = '';

                    const formData = new FormData();
                    formData.append('contact_id', this.activeContact.id);
                    formData.append('message', msgText);

                    try {
                        const response = await fetch('api/send_message.php', {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();
                        if (result.success) {
                            // Message will be picked up by SSE, or we can optimistic update
                        } else {
                            alert('Error: ' + (result.error || 'Failed to send message'));
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Error sending message');
                    }
                },

                scrollToBottom() {
                    setTimeout(() => {
                        if (this.$refs.messageList) {
                            this.$refs.messageList.scrollTop = this.$refs.messageList.scrollHeight;
                        }
                    }, 50);
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    const now = new Date();
                    if (d.toDateString() === now.toDateString()) {
                        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }
                    return d.toLocaleDateString();
                },

                formatTime(dateStr) {
                    const d = new Date(dateStr);
                    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
    <!-- New Chat Modal -->
    <div x-show="showNewChatModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-[#0b141a]/80 backdrop-blur-sm" @click="showNewChatModal = false"></div>
        <div class="bg-[#1f2c33] w-full max-w-md rounded-2xl shadow-2xl border border-gray-700/30 relative z-10 overflow-hidden"
            x-show="showNewChatModal" x-transition>
            <div class="px-8 py-6 border-b border-gray-700/30 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Start New Chat</h3>
                <button @click="showNewChatModal = false" class="text-gray-400 hover:text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form @submit.prevent="sendNewChat()" class="p-8 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Phone Number (with country code)</label>
                    <input type="text" x-model="newChat.phone" placeholder="e.g. 905001234567" required
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Name (Optional)</label>
                    <input type="text" x-model="newChat.name" placeholder="Contact Name"
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Select Template</label>
                    <select x-model="newChat.template"
                        @change="newChat.language = templates.find(t => t.name === $event.target.value).language"
                        required
                        class="w-full bg-[#2a3942] border border-gray-600 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-[#25D366] outline-none">
                        <option value="">Choose a template...</option>
                        <template x-for="tpl in templates" :key="tpl.name">
                            <option :value="tpl.name" x-text="tpl.name"></option>
                        </template>
                    </select>
                </div>
                <div x-show="newChat.template" class="bg-[#2a3942] rounded-xl p-4 border border-gray-700/50">
                    <p class="text-xs text-gray-500 uppercase tracking-widest mb-2">Template Preview</p>
                    <p class="text-sm text-gray-300" x-text="getSelectedTemplateBody()"></p>
                </div>
                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-[#25D366] text-[#0b141a] font-bold py-3 rounded-xl hover:bg-[#1db954] transition-all">
                        Send & Start Chat
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>