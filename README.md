# Professional WhatsApp CRM Panel (Native PHP)

A premium, fast, and light-weight WhatsApp Management Panel built with native PHP, Tailwind CSS, and Alpine.js. This project allows multi-user management (Admin/Agent) and leverages the powerful **WhatsApp Cloud API**.

## 🚀 Powered by `sayborok/php-whatsapp-cloud-api`

This project is built using the [php-whatsapp-cloud-api](https://github.com/sayborok/php-whatsapp-cloud-api) library. It provides a seamless object-oriented wrapper for the WhatsApp Cloud API, making it easy to send text, media, and template messages.

### Why this library?
- **Native PHP**: No heavy dependencies, pure performance.
- **Easy Integration**: PSR-4 compliant and developer-friendly.
- **Full Support**: Supports all major WhatsApp message types.

## ✨ Features

- **Real-time Messaging**: Instant message updates using Server-Sent Events (SSE).
- **Template Management**: Create, list, monitor, and delete WhatsApp Message Templates directly from the Admin panel.
- **New Chat Initiation**: Start conversations with new numbers using mandated WhatsApp Templates.
- **User Management**: Multi-agent support with Admin and Agent roles.
- **Auto-Installation**: Self-healing database initialization system.
- **Premium UI**: Modern dark-themed interface built for efficiency.

## 🛠️ Requirements

- PHP 8.1 or higher
- MySQL 5.7+ / MariaDB
- Composer
- A WhatsApp Business Account (WABA)

## 📦 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/sayborok/whatsapp-crm-panel.git
   cd whatsapp-crm-panel
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Database Setup:**
   - Create a database named `whatsapp_crm`.
   - The application will automatically attempt to import `schema.sql` on first run, or you can import it manually.
   - Configure your credentials in `config/database.php`.

4. **Web Server:**
   - Ensure your document root points to the project directory.

5. **Initial Login:**
   - **URL:** `http://your-domain/public/index.php`
   - **Username:** `admin`
   - **Password:** `admin`

## ⚙️ Configuration

1. Go to **Settings** in the Admin panel.
2. Enter your **WhatsApp Access Token**, **Phone Number ID**, and **WABA ID**.
3. Set up your **Webhook** using the provided URL in the settings page.

## 🤝 Contribution

This project is open for contributions. If you'd like to improve the UI or add new features, feel free to fork and submit a PR!

## 📜 License

MIT License - Created by [sayborok](https://github.com/sayborok)
