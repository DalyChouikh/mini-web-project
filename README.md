# Minimal Blog

A modern, minimalist interactive blog built with vanilla JavaScript, featuring a clean MVC architecture, dark mode, real-time search, and a smooth single-page application experience.

## ✨ Features

- **Real-time Search** - Filter articles instantly as you type
- **Dark/Light Mode** - Persistent theme switcher with localStorage
- **SPA Navigation** - Hash-based routing for smooth article transitions
- **Comment System** - Interactive comments stored in localStorage
- **Reading Progress** - Visual scroll indicator at the top
- **Back to Top** - Animated floating button for quick navigation
- **Social Sharing** - Simulated sharing to Twitter, LinkedIn, and clipboard
- **Responsive Design** - Mobile-first approach with fluid layouts
- **API Integration** - Fetches articles from MockAPI with offline fallback

## 🛠️ Tech Stack

- **HTML5** - Semantic markup with modern elements
- **CSS3** - Custom properties, Grid, Flexbox, smooth transitions
- **JavaScript (ES6+)** - Modular architecture with MVC pattern
- **LocalStorage** - Client-side persistence for theme and comments

## 📁 Project Structure

```
mini-project/
├── index.html
├── styles/
│   ├── base.css      # Core styles and components
│   └── themes.css    # Light/dark theme variables
├── src/
│   ├── data/
│   │   ├── apiClient.js        # API integration
│   │   └── localArticles.js    # Fallback data
│   ├── models/
│   │   ├── ArticleModel.js     # Article data management
│   │   ├── CommentModel.js     # Comment CRUD operations
│   │   ├── ThemeModel.js       # Theme state management
│   │   └── RouterModel.js      # Route parsing and state
│   ├── views/
│   │   ├── HeaderView.js       # Search and theme toggle
│   │   ├── ArticleListView.js  # Article grid rendering
│   │   ├── ArticleDetailView.js # Single article display
│   │   ├── CommentView.js      # Comment list and form
│   │   ├── ScrollIndicatorView.js
│   │   ├── BackToTopView.js
│   │   └── ToastView.js        # Notification system
│   ├── controllers/
│   │   ├── SearchController.js
│   │   ├── CommentController.js
│   │   ├── ThemeController.js
│   │   ├── RouterController.js
│   │   └── AppController.js    # Main orchestrator
│   └── main.js                 # Application entry point
└── README.md
```

## 🚀 Getting Started

### Prerequisites

- A modern web browser with ES6 module support
- A local web server (required for ES modules)

### Running Locally

1. Clone the repository:
```bash
git clone https://github.com/DalyChouikh/mini-web-project.git
cd mini-web-project
```

2. Start a local server:

**Using Python 3:**
```bash
python3 -m http.server 8000
```

**Using Node.js:**
```bash
npx serve
```

**Using PHP:**
```bash
php -S localhost:8000
```

3. Open your browser and navigate to:
```
http://localhost:8000
```

## 🎯 Architecture

### MVC Pattern

**Models** - Data and business logic
- `ArticleModel` - Manages article data, search, and API/local fallback
- `CommentModel` - Handles comment storage in localStorage
- `ThemeModel` - Manages theme persistence
- `RouterModel` - Parses URL hash for navigation state

**Views** - DOM rendering and user interface
- Each view is responsible for a specific UI component
- Views expose binding methods for user interactions
- No business logic in views

**Controllers** - Application flow and coordination
- Connect models and views
- Handle user events and update state
- Coordinate between different parts of the application

### Data Flow

1. User interaction triggers view event
2. View calls bound controller handler
3. Controller updates model(s)
4. Controller tells view(s) to re-render with new data
5. View updates DOM

## 🌐 API Integration

Articles are fetched from MockAPI:
```
https://691a3adc2d8d7855756e387c.mockapi.io/api/v1/articles
```

**Fallback Strategy:**
- If API call fails, the app loads from `localArticles.js`
- A toast notification informs the user about offline mode

**Article Schema:**
```javascript
{
  id: string,
  title: string,
  summary: string,
  content: string,
  tags: string (comma-separated),
  createdAt: ISO date string or timestamp,
  readTime: number,
  author: string,
  imageUrl: string
}
```

## 💾 LocalStorage

The app stores two items:

- `blog-theme` - Current theme (`"light"` or `"dark"`)
- `blog-comments` - Comments object keyed by article ID

## 🎨 Theming

CSS custom properties make theming straightforward:

```css
[data-theme="light"] {
  --bg-primary: #fafbfc;
  --text-primary: #111827;
  --accent-primary: #3b82f6;
  /* ... */
}

[data-theme="dark"] {
  --bg-primary: #0a0e1a;
  --text-primary: #f9fafb;
  --accent-primary: #60a5fa;
  /* ... */
}
```

## 📱 Responsive Design

- Mobile-first CSS approach
- Breakpoints at 480px, 768px, and 1024px
- Fluid typography and spacing
- Touch-friendly interactive elements

## 🔒 Security Considerations

- No external dependencies or CDNs
- Client-side only - no server vulnerabilities
- XSS protection through proper DOM manipulation
- No sensitive data stored in localStorage

## 📝 License

This project is open source and available for educational purposes.

## 👤 Author

Daly Chouikh
- GitHub: [@DalyChouikh](https://github.com/DalyChouikh)
