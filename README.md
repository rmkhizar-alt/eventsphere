# Laravel Boilerplate

Full-stack starter structure — **Laravel 13 + MySQL-ready + Tailwind CSS + GSAP + Alpine.js**.
3 teammates feature-wise kaam ke liye banaya gaya clean, organized boilerplate.

---

## Setup (naya machine par)

```bash
# 1. Dependencies install
composer install
npm install

# 2. Environment setup
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate

# 3. Database (default: SQLite — zero setup)
php artisan migrate --seed

# MySQL use karna ho to .env mein DB_CONNECTION=mysql karo aur
# DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD uncomment karo.

# 4. Run
npm run dev                  # Vite dev server (terminal 1)
php artisan serve            # http://localhost:8000 (terminal 2)
```

**Test login:** `test@example.com` (seeder se bana hai)

---

## Folder Structure

```
app/
  Http/Controllers/
    PostController.php        # PATTERN DEMO — isko follow karo
    DashboardController.php   # Single-action controller example
  Models/
    User.php
    Post.php                  # Fillable, casts, relationships, scope example
database/
  migrations/                 # posts table migration (pattern reference)
  seeders/
    DatabaseSeeder.php        # Sab seeders yahan call karo
    PostSeeder.php
resources/
  views/
    layouts/
      app.blade.php           # MASTER layout — navbar + footer + content
      navigation.blade.php    # Responsive navbar (mobile menu included)
      footer.blade.php
      guest.blade.php         # Auth pages ka layout (login/register)
    components/ui/            # REUSABLE COMPONENTS
      button.blade.php        # variants: primary | secondary | danger (+ href support)
      card.blade.php
      input.blade.php         # label + validation error display built-in
      modal.blade.php         # Alpine-based, accessible (Escape/backdrop close)
      alert.blade.php         # variants: success | error | warning | info
    pages/
      home.blade.php          # Landing page (GSAP hero + features)
      posts/index.blade.php   # Listing + pagination
      posts/show.blade.php    # Detail page
    dashboard.blade.php       # Sidebar + stats layout
    auth/                     # Breeze auth pages (customized design)
  css/app.css                 # THEME TOKENS yahan hain (:root block)
  js/
    app.js                    # Entry point
    bootstrap.js              # Alpine.js init
    gsap/
      animations.js           # Hero intro, hover effects, page transitions
      scroll-effects.js       # ScrollTrigger reveals
routes/web.php                # Home, Posts, Dashboard, Profile routes
```

---

## Naming Conventions

| Cheez             | Convention                          | Example                    |
|-------------------|-------------------------------------|----------------------------|
| Controllers       | Singular PascalCase + `Controller`  | `PostController`           |
| Models            | Singular PascalCase                 | `Post`, `User`             |
| Tables            | Plural snake_case                   | `posts`, `post_comments`   |
| Migrations        | `{action}_{table}_table`            | `create_posts_table`       |
| Routes            | kebab-case URLs, snake_case names   | `route('posts.index')`     |
| Blade components  | `<x-ui.kebab-name>`                 | `<x-ui.button>`            |
| Views             | `pages/{feature}/{page}.blade.php`  | `pages/posts/index`        |
| CSS classes       | Tailwind utilities only             | brand-* tokens use karo    |

## Naya Feature Kaise Add Karein (Pattern)

1. **Migration:** `php artisan make:migration create_xxx_table`
2. **Model:** `php artisan make:model Xxx` (+ fillable/casts/relations)
3. **Seeder/Factory** (agar test data chahiye)
4. **Controller:** `php artisan make:model -c` ya manually — logic models mein rakho
5. **Route:** `routes/web.php` mein add karo (named route)
6. **View:** `resources/views/pages/{feature}/` mein blade file

> Reference implementation: **Post entity** (migration → model → factory → seeder → controller → routes → views)

## Design System

- **Fonts:** Inter (Google Fonts via bunny.net)
- **Colors:** `resources/css/app.css` ke `:root` CSS variables — pura theme ek jagah se change hota hai (`brand-50` … `brand-950`)
- **Components:** `<x-ui.*>` — button/card/input/modal/alert sab ready
- **Animations:** GSAP — `[data-animate]` (hero intro), `[data-scroll-reveal]` (scroll), `data-animate-hover` (button hover). `prefers-reduced-motion` automatically respect hota hai.

## Included Packages

- **laravel/breeze** — auth scaffolding (login/register/logout/password reset)
- **laravel/sanctum** — API tokens (future SPA/mobile ke liye; `routes/api.php` ready)
- **tailwindcss + postcss + autoprefixer** — styling
- **gsap** — animations (ScrollTrigger included)
- **alpinejs** — lightweight interactivity (modals, mobile menu)
