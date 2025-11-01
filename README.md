
<div align="center">
  <img src="assets/logo_micro.png" alt="Microlite Logo" width="200" />
  <h1>Microlite</h1>
  <p><strong>Microlite is a fast, lightweight, modern PHP microframework inspired by Laravel and fully PSR-15 compliant.
</strong></p>
</div>
---

### Keywords
php microframework, lightweight php framework, psr-15 framework, php router, php middleware, laravel style microframework, fast php framework, minimal php framework, microlite php

---
<div align="center">

# About

**Microlite is a modern, ultra-lightweight PHP microframework built for developers who want simplicity, speed, and complete control over their application architecture. Designed for projects where a full-stack framework is unnecessary or too heavy, Microlite provides the essential tools you need — and nothing you don’t.**

**Inspired by the elegance and developer-friendly structure of Laravel, Microlite offers a familiar and intuitive workflow without requiring you to learn a new ecosystem. If you enjoy the Laravel style but need something significantly smaller and faster, Microlite is the perfect fit.**

**Fully PSR-15 compliant, minimal by design, and highly flexible, Microlite allows you to extend or customize every layer. Whether you're building micro-services, APIs, or small high-performance applications, Microlite keeps your stack clean and efficient.**

**Contributions are welcome — feel free to star the project, open issues, or submit PRs!**


[![PHP >= 8.1](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg?style=flat-square)](https://php.net)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/mhvefgh/microlite.svg?style=flat-square)](https://packagist.org/packages/mhvefgh/microlite)
[![Tests](https://img.shields.io/badge/tests-100%25%20passing-brightgreen.svg?style=flat-square)](#testing)
[![Downloads](https://img.shields.io/packagist/dt/mhvefgh/microlite.svg?style=flat-square)](https://packagist.org/packages/mhvefgh/microlite)

</div>

---

## Features

| Feature | Description |
|--------|-------------|
| **Zero Bloat** | Only what you need — no heavy dependencies |
| **FastRoute** | Blazing fast routing with `nikic/fast-route` |
| **Medoo DB** | Lightweight database layer (MySQL, PostgreSQL, SQLite) |
| **PSR-15 Middleware** | Full middleware stack support |
| **.env Config** | Environment-based configuration |
| **CLI Tools** | Built-in Symfony Console commands |
| **Testing Ready** | PHPUnit + example tests included |

---

## Installation

```bash
composer create-project mhvefgh/microlite my-app
cd my-app
cp .env.example .env
php -S localhost:8000 -t public
```
---
## Quick Start

**Open: http://localhost:8000**


## Getting started

---
```
// Route:

$r->addRoute('GET', '/hello/{name}', ['App\Controllers\HomeController', 'hello']);

// Controller:

// app/Controllers/HomeController.php

// Sample Create Controller:

namespace App\Controllers;

<?php
namespace App\Controllers;

class HomeController
{
    public function hello($name)
    {
        return "<h1>Hello, {$name}!</h1>";
    }
}

//Sample Create Middelweare:

<?php
namespace App\Middleware;

use Src\Core\Middleware;
use Src\Core\Request;

class ExampleMiddleware extends Middleware {
    public function handle(Request $request, callable $next) {
        // simple example: add header (can't set headers here without response object)
        // continue
        return $next($request);
    }
}

```


### Visit:
/hello/World → Hello, World!

## Project Structure

my-app/
├── app/              # Your controllers & middleware & views
├── public/           # Web entry point
├── src/              # Framework core
├── tests/            # PHPUnit tests
├── .env.example      # Environment template
└── composer.json     # Dependencies & autoload


## Testing

composer test

## Author

Mohammad Hossein Vefgh
Full-Stack Engineer | Open Source Contributor

GitHub: https://github.com/mhvefgh
Email: vefgh.m.hossein@gmail.com
LinkedIn: [https://www.linkedin.com/in/mohammad-hossein-vefgh-20b533164]



## License

The Microlite Php  framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).