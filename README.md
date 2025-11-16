# Minimalist Interactive Blog

A small interactive blog built for a university assignment using semantic HTML5, modern CSS3, and modular JavaScript with an MVC-inspired architecture.

## Features

- Real-time article search and filtering
- Article list and detail views with lightweight SPA navigation using URL hash
- Interactive comment system stored in `localStorage`
- Persistent light/dark mode with `localStorage`
- Scroll-based reading progress indicator
- Animated "Back to top" button
- Social sharing simulation with non-blocking toast notifications
- Optional article loading from a `mockapi.io` endpoint, with offline fallback data

## Tech stack

- HTML5 (semantic elements)
- CSS3 (variables, transitions, responsive layout)
- Vanilla JavaScript (ES modules)
- `localStorage` for theme and comments

## Running the project

Because the project uses ES modules, it should be served over HTTP rather than opened directly as a local file.

From the project root:

```bash
python3 -m http.server 8000
```

Then open:

- http://localhost:8000/

## API configuration

Articles are fetched from the mock API:

- `https://691a3adc2d8d7855756e387c.mockapi.io/api/v1/articles`

If the API is not reachable, the app falls back to a small set of local articles defined in `src/data/localArticles.js` and shows a toast.

## Architecture overview

- Models (`src/models`)
  - `ArticleModel` – manages articles from API/local, searching and mapping
  - `CommentModel` – handles per-article comments in `localStorage`
  - `ThemeModel` – persists and toggles theme
  - `RouterModel` – stores current route/view state
- Views (`src/views`)
  - `HeaderView` – search input and theme toggle
  - `ArticleListView` – renders the list of article cards
  - `ArticleDetailView` – renders a single article and share buttons
  - `CommentView` – comment list and form
  - `ScrollIndicatorView` – top progress bar
  - `BackToTopView` – floating back-to-top button
  - `ToastView` – bottom toast notifications
- Controllers (`src/controllers`)
  - `SearchController` – search logic
  - `CommentController` – comment lifecycle and validation
  - `ThemeController` – theme initialization and toggling
  - `RouterController` – hash-based navigation between list and detail
  - `AppController` – high-level wiring, scroll indicator, sharing simulation

This structure keeps concerns separated and makes the code easier to understand and extend.
