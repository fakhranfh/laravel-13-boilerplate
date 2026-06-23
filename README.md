# Laravel 13 Boilerplate

A modern, fully-featured Laravel boilerplate built with the latest technologies and best practices. This starter kit includes authentication, email verification, password reset, two-factor authentication, passkeys support, and a complete testing infrastructure.

## Features

### Authentication & Security
- **User Registration & Login** – Modern authentication with email/password
- **Email Verification** – Verify user email addresses before access
- **Password Reset** – Secure password recovery flow

### Testing Infrastructure
- **Pest Testing Framework** – Modern PHP testing with intuitive syntax
- **Feature Tests** – Test application flows and business logic
- **Browser Tests** – Dusk-powered automated browser testing
- **Stress/Load Tests** – K6-based performance testing

### Frontend & Styling
- **Tailwind CSS v4** – Utility-first CSS framework
- **Vite** – Lightning-fast build tool and dev server
- **Laravel Vite Plugin** – Seamless Laravel integration

### Developer Experience
- **Laravel Tinker** – Interactive REPL for testing code
- **Laravel Pail** – Real-time log streaming
- **Husky & Commitlint** – Git hooks and conventional commits
- **Claude Code Integration** – AI-powered development assistance via MCP

## Quick Start

### Prerequisites
- PHP 8.4+
- Composer
- Node.js 18+
- npm

### Installation

1. **Clone and install dependencies:**
   ```bash
   git clone https://github.com/fakhranfh/laravel-13-boilerplate
   cd laravel-13-boilerplate
   composer install
   npm install
   ```

2. **Setup the application:**
   ```bash
   composer run setup
   ```

   This runs:
   - Copies `.env.example` to `.env`
   - Generates application key
   - Runs database migrations
   - Installs npm dependencies
   - Builds frontend assets

3. **Start development servers:**
   ```bash
   composer run dev
   ```

   This concurrently runs:
   - PHP development server
   - Queue listener
   - Vite dev server

### Manual Setup (if needed)

```bash
# Generate app key
php artisan key:generate

# Create SQLite database (or use MySQL)
touch database/database.sqlite
php artisan migrate

# Build frontend
npm run build
```

## Project Structure

```
laravel-13-boilerplate/
├── app/
│   ├── Actions/           # Reusable action classes
│   ├── Http/              # Controllers, middleware, requests
│   ├── Models/            # Eloquent models
│   └── Providers/         # Service providers
├── resources/
│   ├── css/               # Tailwind CSS
│   ├── js/                # Frontend JavaScript
│   └── views/             # Blade templates
├── routes/
│   ├── api.php            # API routes
│   ├── channels.php       # Broadcasting channels
│   └── web.php            # Web routes
├── tests/
│   ├── Feature/           # Feature/integration tests
│   ├── Browser/           # Dusk browser tests
│   ├── Unit/              # Unit tests
│   ├── k6/                # K6 performance tests
│   └── Pest.php           # Pest configuration
├── database/
│   ├── factories/         # Model factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
└── config/                # Application configuration
```

## Available Commands

### Development

```bash
# Start all development servers (PHP, Queue, Vite)
composer run dev

# Start only PHP server
php artisan serve

# Start Vite dev server
npm run dev

# Listen to queue jobs
php artisan queue:listen

# Watch logs in real-time
php artisan pail
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TestName

# Run Dusk browser tests
php artisan dusk

# Run stress test with K6
k6 run tests/k6/<test-file>
```

### Database

```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Create migration
php artisan make:migration create_users_table

# Create model with migration
php artisan make:model Post -m
```

### Artisan

```bash
# List available commands
php artisan list

# Get help for a command
php artisan [command] --help

# Tinker REPL
php artisan tinker
```

## Configuration

### Environment Variables

Key environment variables (see `.env.example` for all options):

```env
APP_NAME="Laravel 13 Boilerplate"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_DATABASE=laravel-13-boilerplate

MAIL_MAILER=log
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Database

By default, the boilerplate uses MySQL. To use SQLite instead:

```bash
# Create SQLite database
touch database/database.sqlite

# Update .env
DB_CONNECTION=sqlite
# Comment out or remove other DB_* variables

# Run migrations
php artisan migrate
```

## Testing

### Writing Tests

```bash
# Create a feature test
php artisan make:test Feature/LoginTest --pest

# Create a unit test
php artisan make:test Unit/ExampleTest --pest --unit

# Create a Dusk browser test
php artisan dusk:make LoginTest
```

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/LoginTest.php

# Run tests matching a pattern
php artisan test --filter=login

# Run browser tests (Dusk)
php artisan dusk

# Run with coverage
php artisan test --coverage
```

### Manual Deployment

For traditional servers:

1. Push code to your server
2. Install dependencies: `composer install --no-dev`
3. Set up environment: `cp .env.example .env` and configure
4. Generate key: `php artisan key:generate`
5. Build assets: `npm install && npm run build`
6. Run migrations: `php artisan migrate`
7. Configure web server to point to `public/` directory

## Architecture Decisions

### Pest Over PHPUnit
Uses [Pest](https://pestphp.com/) for more readable, expressive tests with a modern syntax.

### Laravel Fortify
Built on [Laravel Fortify](https://github.com/laravel/fortify) for robust, customizable authentication without scaffolding overhead.

### Tailwind CSS
Utility-first approach with [Tailwind CSS v4](https://tailwindcss.com/) for rapid UI development and smaller CSS bundles.

### Database-Driven Sessions & Cache
Configured to use database for sessions and cache, suitable for development and testing without external dependencies.

## Performance Considerations

- Database queries use efficient eager loading (Eloquent relationships)
- Assets are automatically minified in production via Vite
- Database migrations are run with proper indexing
- K6 stress tests included for performance validation

## Troubleshooting

### Application Key Not Set

```bash
php artisan key:generate
```

### Frontend Changes Not Reflecting

If Blade template or asset changes aren't visible:

```bash
# Rebuild assets
npm run build

# Or start dev server with hot reload
npm run dev
```

### Database Connection Issues

Verify database credentials in `.env`:

```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo();
```

### Permission Issues

Ensure Laravel has write access to:

```bash
chmod -R 775 storage bootstrap/cache
```

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Pest Documentation](https://pestphp.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Laravel Dusk](https://laravel.com/docs/dusk)
- [Laravel Fortify](https://github.com/laravel/fortify)

## License

This boilerplate is open source software licensed under the [MIT license](LICENSE).
