# Minimal Blog

A modern, minimalist interactive blog built with PHP and vanilla JavaScript, featuring server-side rendering, MySQL database persistence, an admin panel for content management, and a comment moderation system.

## ✨ Features

### Public Features

- **Server-side Search** - Search articles by title, content, author, or tags with pagination
- **Dark/Light Mode** - Persistent theme switcher with localStorage
- **Comment System** - Submit comments via AJAX with admin moderation before publication
- **Reading Progress** - Visual scroll indicator at the top
- **Back to Top** - Animated floating button for quick navigation
- **Social Sharing** - Share articles to Twitter, LinkedIn, or copy link
- **Responsive Design** - Mobile-first approach with fluid layouts
- **RSS Feed** - Syndication feed for blog articles

### Admin Panel

- **Secure Authentication** - Password hashing with bcrypt
- **Articles Management** - Create, edit, delete articles (CRUD)
- **Comments Moderation** - Approve or delete user comments
- **Dashboard** - Overview of articles and pending comments

## 🛠️ Tech Stack

- **PHP 8.2** - Server-side rendering and business logic
- **MySQL 8.0** - Relational database for persistent storage
- **HTML5** - Semantic markup with modern elements
- **CSS3** - Custom properties, Grid, Flexbox, smooth transitions
- **JavaScript (ES6+)** - UI enhancements (theme toggle, scroll indicator, AJAX comments)
- **Docker** - Containerized development environment
- **PDO** - Secure database access with prepared statements

## 📁 Project Structure

```
mini-web-project/
├── index.php                 # Main page with article list, search & pagination
├── article.php               # Article detail page with comments
├── rss.php                   # RSS feed generator
├── database.sql              # Database schema and sample data
├── docker-compose.yml        # Docker services configuration
├── Dockerfile                # PHP-Apache container setup
│
├── includes/
│   ├── config.php            # Database connection & utility functions
│   ├── header.php            # Common HTML header
│   └── footer.php            # Common HTML footer
│
├── ajax/
│   └── comment.php           # AJAX endpoint for comment submission
│
├── js/
│   └── app.js                # Client-side JS (theme, scroll, AJAX)
│
├── admin/
│   ├── index.php             # Admin dashboard
│   ├── login.php             # Admin authentication
│   ├── logout.php            # Session logout
│   ├── articles.php          # Articles list management
│   ├── article-form.php      # Article create/edit form
│   ├── comments.php          # Comments moderation
│   └── includes/
│       └── auth.php          # Authentication middleware
│
├── styles/
│   ├── base.css              # Core styles and components
│   ├── themes.css            # Light/dark theme variables
│   └── admin.css             # Admin panel styles
│
└── src/                      # Original JS MVC architecture (reference)
    ├── data/
    ├── models/
    ├── views/
    ├── controllers/
    └── main.js
```

## 🚀 Getting Started

### Prerequisites

- **Docker** and **Docker Compose** installed
- A modern web browser

### Running with Docker (Recommended)

1. Clone the repository:

```bash
git clone https://github.com/DalyChouikh/mini-web-project.git
cd mini-web-project
```

2. Start the Docker containers:

```bash
docker-compose up -d
```

3. Wait for containers to start, then access:
   - **Blog**: <http://localhost:8000>
   - **Admin Panel**: <http://localhost:8000/admin/> (credentials: `admin` / `admin123`)
   - **phpMyAdmin**: <http://localhost:8080> (credentials: `root` / `root123`)
   - **RSS Feed**: <http://localhost:8000/rss.php>

4. To stop the containers:

```bash
docker-compose down
```

### Docker Services

| Service | Port | Description |
|---------|------|-------------|
| PHP-Apache | 8000 | Web server with PHP 8.2 |
| MySQL | 3306 | Database server |
| phpMyAdmin | 8080 | Database administration UI |

## 🗄️ Database Schema

### Tables

**articles**

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| title | VARCHAR(255) | Article title |
| summary | TEXT | Short description |
| content | TEXT | Full article content |
| author | VARCHAR(100) | Author name |
| tags | VARCHAR(255) | Comma-separated tags |
| image_url | VARCHAR(500) | Featured image URL |
| read_time | INT | Estimated reading time (minutes) |
| created_at | TIMESTAMP | Publication date |
| updated_at | TIMESTAMP | Last modification date |

**comments**

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| article_id | INT | Foreign key to articles |
| author_name | VARCHAR(100) | Commenter name |
| content | TEXT | Comment text |
| is_approved | TINYINT(1) | Moderation status (0=pending, 1=approved) |
| created_at | TIMESTAMP | Submission date |

**admins**

| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary key |
| username | VARCHAR(50) | Login username |
| password | VARCHAR(255) | Bcrypt hashed password |
| created_at | TIMESTAMP | Account creation date |

## 🔄 RSS Feed

### What is RSS?

**RSS (Really Simple Syndication)** is an XML-based format for distributing and sharing web content. It allows users to subscribe to updates from websites without having to visit them manually.

### How RSS Works

1. **Publisher creates a feed** - The website generates an XML file (`rss.php`) containing article summaries
2. **User subscribes** - Users add the feed URL to their RSS reader (Feedly, Inoreader, etc.)
3. **Automatic updates** - The RSS reader periodically checks the feed and displays new articles

### Benefits of RSS

- **No algorithm** - Users see ALL updates, not filtered by an algorithm
- **Privacy** - No tracking, no ads, no personal data collection
- **Aggregation** - Follow multiple blogs in one place
- **Offline reading** - Many readers cache content for offline access

### Our RSS Feed

Access the blog's RSS feed at: `http://localhost:8000/rss.php`

The feed includes:

- Channel information (title, description, link)
- Last 50 articles with title, summary, author, date, and link
- Standard RSS 2.0 format compatible with all RSS readers

```xml
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Minimal Blog</title>
    <description>A minimalist blog about technology and development</description>
    <link>http://localhost:8000</link>
    <item>
      <title>Article Title</title>
      <description>Article summary...</description>
      <link>http://localhost:8000/article.php?id=1</link>
      <author>Author Name</author>
      <pubDate>Wed, 04 Dec 2024 10:00:00 +0000</pubDate>
    </item>
    ...
  </channel>
</rss>
```

## 🔗 JavaScript ↔ PHP Communication (AJAX)

The project demonstrates JavaScript-PHP communication through the comment submission system:

### How it Works

1. **User fills the comment form** on `article.php`
2. **JavaScript intercepts the form submission** (`js/app.js`)
3. **AJAX POST request** is sent to `ajax/comment.php` with JSON data
4. **PHP processes the request**:
   - Validates input data
   - Inserts comment into MySQL (with `is_approved = 0`)
   - Returns JSON response
5. **JavaScript handles the response**:
   - Shows success/error message via toast notification
   - Resets form on success

### Code Example

**JavaScript (js/app.js):**

```javascript
const response = await fetch('ajax/comment.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ article_id, author_name, content })
});
const result = await response.json();
```

**PHP (ajax/comment.php):**

```php
$data = json_decode(file_get_contents('php://input'), true);
// Validate and insert into database
echo json_encode(['success' => true, 'message' => '...']);
```

## 🎯 Architecture

### Server-Side Rendering

Unlike the original SPA version, PHP now generates complete HTML pages on the server:

1. **User requests a page** (e.g., `/article.php?id=1`)
2. **PHP queries the database** using PDO prepared statements
3. **PHP generates full HTML** and sends it to the browser
4. **JavaScript enhances the UI** (theme toggle, scroll indicator, AJAX comments)

### Security Features

- **PDO Prepared Statements** - Protection against SQL injection
- **Password Hashing** - Bcrypt with `password_hash()` / `password_verify()`
- **Input Sanitization** - `htmlspecialchars()` for XSS prevention
- **Session Management** - Secure admin authentication
- **Comment Moderation** - Manual approval before publication

## 🎨 Theming

CSS custom properties enable easy theming:

```css
[data-theme="light"] {
  --bg-primary: #fafbfc;
  --text-primary: #111827;
  --accent-primary: #3b82f6;
}

[data-theme="dark"] {
  --bg-primary: #0a0e1a;
  --text-primary: #f9fafb;
  --accent-primary: #60a5fa;
}
```

Theme preference is stored in `localStorage` and applied on page load.

## 📱 Responsive Design

- Mobile-first CSS approach
- Breakpoints at 480px, 768px, and 1024px
- Fluid typography and spacing
- Touch-friendly interactive elements

## 🔒 Security Considerations

- **No external frameworks** - Reduced attack surface
- **PDO with prepared statements** - SQL injection prevention
- **Password hashing** - Bcrypt for secure password storage
- **Output escaping** - XSS prevention with `htmlspecialchars()`
- **Comment moderation** - Spam protection through manual approval

## 📝 License

This project is open source and available for educational purposes.

## 👤 Author

Daly Chouikh

- GitHub: [@DalyChouikh](https://github.com/DalyChouikh)
