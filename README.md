# Site Blog On PHP

Little blog site made for learning PHP. Style is not the priority — the goal of this project is to practice backend development with PHP.

## Stack

Required to install and run this project: [PHP 8.5](https://www.php.net/downloads.php?os=linux&osvariant=linux-debian&version=8.5), [Composer](https://getcomposer.org/download/), and [TailwindCSS](#TailwindCSS).

- Backend (main language): [PHP 8.5](https://www.php.net/)
- Database: [SQLite](https://sqlite.org/)
- Dependency manager for PHP: [Composer](https://getcomposer.org/)
- CSS styles: [TailwindCSS](https://tailwindcss.com/)

## Installation

```bash
git clone git@github.com:WanderVafla/PHP-blog.git
cd PHP-blog
composer install
php -S localhost:8000 -t public
```

The SQLite database initializes itself automatically on first run — no manual setup needed.

### TailwindCSS

You need the TailwindCSS binary installed to build the CSS.

**Arch Linux / AUR:**
```bash
yay -S tailwindcss-bin
```

**Linux (universal):**
```bash
curl -sLO https://github.com/tailwindlabs/tailwindcss/releases/latest/download/tailwindcss-linux-x64
chmod +x tailwindcss-linux-x64
sudo mv tailwindcss-linux-x64 /usr/local/bin/tailwindcss
```

Once installed, generate the config and build the CSS:
```bash
tailwindcss -i ./src/input.css -o ./public/output.css --watch
```

**NixOS / Flake:** see the [dedicated section below](#installation-via-nix-flake) — it sets up PHP, Composer and Tailwind for you.

## Installation via Nix flake

If you use Nix, there's a `flake.nix` that sets up the whole dev environment in one shell — PHP, Composer, and TailwindCSS 4, no manual binary install needed.

```bash
nix develop
```

On first run, the shell hook automatically:
- runs `composer install` if `vendor/` doesn't exist yet,
- runs `tailwindcss init` if `tailwind.config.js` is missing,
- creates a starter `src/input.css` if it doesn't exist yet.

It also sets up a few aliases so you don't have to remember the raw commands. Minimum needed to get everything running (Tailwind watcher + PHP server together):

```bash
dev
```

Or run each part separately:

PHP dev server only:
```bash
serve
```

Tailwind compiler only (watch mode):
```bash
watch-css
```

## Pages on site

- **Nav bar** -> logo redirects to the home page. Login and Register buttons are always visible at the top, including on the [login](http://localhost:8000/login) and [register](http://localhost:8000/singup) pages themselves.
- **[Home](http://localhost:8000/)** -> lists all existing posts, with a button to create a new one.
- **Opened post** -> shows the post's title, body, and chosen category. If you're the owner of the post or an admin, you also get Edit and Remove buttons.
- **[Create post](http://localhost:8000/createPost)** -> title, description (body of the post), category choice, and image upload. Requires login.
- **[Profile](http://localhost:8000/profil)** -> change your user data (username, email, password), and see the list of your own posts. Requires login. (Known bug: post categories aren't shown here — see Technical notes.)
- **[Login](http://localhost:8000/login)** -> log in to an existing account.
- **[Register](http://localhost:8000/singup)** -> create a new user account.

### Users

- Passwords are encrypted.
- A user can have the role `user` or `admin`.
- `user` can edit and remove their own posts.
- `admin` can edit and remove any post.
- A `user` can only become `admin` by having the role changed directly in the database.

## Technical notes / what could be better

- **Modal window**: implemented by parsing a query parameter from the URL, combined with CSS to toggle visibility. You can see it on the profile page when changing user data. The parameter is `?change=` followed by one of:
  - `password`
  - `name`
  - `email`

- **Custom Tailwind theme/utilities** (`src/input.css`): a few reusable pieces are defined instead of repeating raw utility classes everywhere:
  - `@theme` adds a custom `--shadow-content-post` glow shadow, used on post content.
  - `@layer base` sets default styles for `h1` and `button` (including hover/focus states on buttons).
  - `@utility` defines shortcuts: `main-default` (page padding), `main-form` (centered form layout with shadow), `link_text` (styled link), and `error` (error message color).

  This keeps some consistency, but it also means styling logic is split between `input.css` and inline classes in the templates — could be organized better.

- Some methods in the codebase are doing too many different choices/branches at once — could use refactoring.
- **Known bug**: on the profile page, posts don't show their category.