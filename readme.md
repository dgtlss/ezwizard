<p align="center">
  <a href="https://github.com/dgtlss/ezwizard">
    <img src="ezwizicon.png" alt="Laravel EzWizard" width="150px">
  </a>
</p>

<h1 align="center">EzWizard v1.1.0</h1>

EzWizard is an automation tool for Laravel that simplifies the process of setting up your Laravel application by automating the installation of various Laravel packages. With EzWizard, you can easily add functionality to your projects, such as authentication, image manipulation, social login, PDF generation, and more, with minimal manual effort.

## Features

- Streamlines the installation of a wide range of Laravel packages.
- Automates `composer require` commands and necessary Artisan commands.
- Checks database connection at the end of the installation flow and runs migrations.

## Prerequisites

Before you begin, ensure you have a Laravel project set up and a database connection properly configured, as EzWizard will attempt to run migrations after the installation process is complete.

## Installation


1. Install EzWizard via Composer:
```composer require dgtlss/ezwizard```

2. To start the EzWizard initialization process, run: 
```php artisan ezwiz:init```

3. Follow the instructions provided to select and install the desired packages.

## Supported Packages

EzWizard supports a variety of packages categorized into functionality areas for ease of reference:

### Laravel Packages

- *Laravel Breeze* - Simple authentication scaffolding.
- *Laravel Jetstream* - Advanced authentication scaffolding.
- *Laravel UI* - Authentication scaffolding.
- *Laravel Socialite* - OAuth authentication.
- *Laravel Sanctum* - Featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs.
- *Laravel Fortify* - frontend agnostic authentication backend implementation for Laravel.
- *Laravel Livewire* - A full-stack framework for Laravel that makes building dynamic interfaces simple. 
- *Laravel Cashier* - An expressive, fluent interface to Stripe & Paddle's subscription billing services. 
- *Laravel Folio* - Powerful page based router designed to simplify routing in Laravel applications.
- *Intervention Image* - Image handling and manipulation library.
- *DomPDF* - A DOMPDF Wrapper for Laravel.

### Monitoring & Debugging

- *Laravel Telescope* - Elegant debug assistant for the Laravel framework.
- *Laravel Horizon* - Dashboard and configuration for Laravel queues.
- *Laravel Debugbar* - Debug bar for Laravel.
- *Clockwork* - PHP development tool for the Laravel framework.

### Misc

- *Cooker* - Easy to use frontend compiler by [genericmilk](https://github.com/genericmilk/cooker)
 for laravel
- *Tailwind CSS* - A utility-first CSS framework for rapidly building custom designs.

## Contributing

Contributions are welcome! If you'd like to contribute, please fork the repository, make your changes, and submit a pull request.

## License

EzWizard is open-sourced software licensed under the MIT license.

