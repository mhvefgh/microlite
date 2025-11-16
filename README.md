
<div align="center">
  <img src="assets/logo_micro.png" alt="Microlite Logo" width="200" />
  <h1>Microlite</h1>
  <p><strong>A fast, lightweight, modern PHP microframework inspired by Laravel — fully PSR-15 compliant.</strong></p>
  <p>Build APIs, microservices, or small apps with zero bloat. Simple, speedy, and scalable.</p>
</div>

---

<div align="center">

# About

**Microlite is a modern, ultra-lightweight PHP microframework built for developers who want simplicity, speed, and complete control over their application architecture. Designed for projects where a full-stack framework is unnecessary or too heavy, Microlite provides the essential tools you need — and nothing you don’t.**

**Inspired by the elegance and developer-friendly structure of Laravel, Microlite offers a familiar and intuitive workflow without requiring you to learn a new ecosystem. If you enjoy the Laravel style but need something significantly smaller and faster, Microlite is the perfect fit.**

**Fully PSR-15 compliant, minimal by design, and highly flexible, Microlite allows you to extend or customize every layer. Whether you're building micro-services, APIs, or small high-performance applications, Microlite keeps your stack clean and efficient.**

**Contributions are welcome — feel free to star the project, open issues, or submit PRs! 🚀**


[![PHP >= 8.1](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg?style=flat-square)](https://php.net)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/mhvefgh/microlite.svg?style=flat-square)](https://packagist.org/packages/mhvefgh/microlite)
[![Tests](https://img.shields.io/badge/tests-100%25%20passing-brightgreen.svg?style=flat-square)](#testing)
[![Downloads](https://img.shields.io/packagist/dt/mhvefgh/microlite.svg?style=flat-square)](https://packagist.org/packages/mhvefgh/microlite)


</div>

---

## Why Microlite?

| Feature           | Microlite       | Laravel       | Slim        |
|-------------------|-----------------|---------------|-------------|
| Size              | ~50KB           | ~10MB+        | ~100KB      |
| Speed             | Ultra-fast      | Medium        | Fast        |
| Learning Curve    | Laravel-like    | High          | Low         |
| Dependencies      | Zero bloat      | Many          | Minimal     |
| Best For          | APIs & Microservices | Full apps | APIs        |

Perfect when you love Laravel's style but hate the overhead.

---

## Features

- **Zero Bloat** — Only what you need
- **FastRoute** — Blazing fast routing
- **Medoo ORM** — Lightweight database layer
- **PSR-15 Middleware** — Full stack support
- **.env Config** — Simple environment management
- **CLI Tools** — Symfony Console commands
- **PHP Views** — With helpers (`view()`, `e()`, `auth()`)
- **Session Auth** — Built-in authentication helper
- **Testing Ready** — PHPUnit + examples

---

## Installation

```bash
composer create-project mhvefgh/microlite my-app
cd my-app
cp .env.example .env
php -S localhost:8000 -t public
```

Open → [http://localhost:8000](http://localhost:8000)

---

## Quick Start

### 1. Routes (`routes/web.php`)
```php
<?php
return function ($app) {
    $router = $app->router();

    $router->get('/', 'HomeController@index');
    $router->get('/hello/{name}', 'HomeController@hello');
    $router->get('/dashboard', 'DashboardController@index')->middleware('auth');
};
```

### 2. Controller (`app/Controllers/HomeController.php`)
```php
<?php
namespace App\Controllers;

use Src\Core\Controller;
use Src\Core\Request;

class HomeController extends Controller
{
    public function index(Request $req): string
    {
        return view('home', [
            'title' => 'Welcome to Microlite',
            'user'  => auth()
        ], 'layouts.main');
    }

    public function hello(Request $req, string $name): string
    {
        return "<h1>Hello, " . e($name) . "!</h1>";
    }
}
```

### 3. View (`resources/views/home.php`)
```php
<h1 class="text-4xl font-bold"><?= $title ?></h1>
<p>Welcome to Microlite! <?= auth() ? 'Logged in as ' . e(auth()->name) : 'Guest' ?></p>
<a href="/hello/World" class="text-blue-600 underline">Say Hello →</a>
```

---

## Database & Model Example

```php
// app/Models/User.php
<?php
namespace App\Models;

use Src\Core\Model;

class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email', 'password'];

    public function save(): bool
    {
        if ($this->password) {
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        }
        return parent::save();
    }
}

// In controller
$user = new User([
    'name' => 'Ali',
    'email' => 'ali@example.com',
    'password' => '123456'
]);
$user->save();
```

---

## Middleware Example

```php
// app/Middleware/AuthMiddleware.php
<?php
namespace App\Middleware;

use Src\Core\Middleware;
use Src\Core\Request;

class AuthMiddleware extends Middleware
{
    public function handle(Request $request, callable $next)
    {
        if (!auth()) redirect('/login');
        return $next($request);
    }
}
```

---

## Project Structure

```
my-app/
├── app/              # Controllers, Models, Middleware
├── public/           # index.php (entry point)
├── resources/views/  # PHP templates
├── routes/           # web.php
├── src/              # Framework core
├── tests/            # PHPUnit tests
├── .env.example
└── composer.json
```

---

## CLI Commands

```bash
# Show Microlite information (default command)
php microlite
php microlite about

# Start the development server (like php artisan serve)
php microlite serve
php microlite serve --host=0.0.0.0 --port=8080

# Generate a new controller
php microlite make:controller UserController
php microlite make:controller Admin/PostController

# Generate a new model
php microlite make:model Post
php microlite make:model ProductCategory

# Clear application cache
php microlite cache:clear

# Get help for any command
php microlite --help
php microlite serve --help
```

### Available Commands

| Command                  | Description                                           |
|--------------------------|-------------------------------------------------------|
| `about`                  | Display Microlite version and environment info        |
| `serve`                  | Start the built-in PHP development server             |
| `make:controller <name>` | Create a new controller class                         |
| `make:model <name>`      | Create a new model class                              |
| `cache:clear`            | Remove all cached files (views, config, routes, etc.) |

**Tip:** Just run `php microlite` with no arguments to see the beautiful welcome screen!

---

## Testing

```bash
composer test
```

---

## Contributing

We love contributions!  

Fork → Create branch → Commit → Push → Pull Request

---

## Author

**Mohammad Hossein Vefgh**  
Full-Stack PHP Developer | Open Source Enthusiast  

- GitHub: [@mhvefgh](https://github.com/mhvefgh)  
- Email: vefgh.m.hossein@gmail.com  
- LinkedIn: [Mohammad Hossein Vefgh](https://www.linkedin.com/in/mohammad-hossein-vefgh-20b533164)

---


## License

Released under the **[MIT License](https://opensource.org/licenses/MIT)**.

Copyright © 2025 [Mohammad Hossein Vefgh](https://github.com/mhvefgh)

---

If you like Microlite, give it a ⭐  

Microlite — Laravel-style, but microlite.
