# Laravel 7.0+ front-end preset for Zurb Foundation

A Laravel front-end scaffolding preset for [Zurb Foundation](https://get.foundation/) - The most advanced responsive front-end framework in the world.

## Installation

1. Install fresh Laravel `>=5.5` project and `cd` into directory containing app,
2. Install the preset via `composer require laravel-frontend-presets/zurb-foundation --dev` (service provider will be auto-detected),
3. Apply one of the following preset configurations:
   1. Use `php artisan ui foundation` for **basic preset without Authentication** including core functions and only welcome page view,
   2. Use `php artisan ui foundation --auth` for **full preset with Authentication** including basic preset, authentication controllers, views and route entries, all in one go (**NOTE:** If you run this command several times, be sure to clean up the duplicated entries from `routes/web.php` file),
4. Run `npm install && npm run dev` to compile your fresh scaffolding,
5. If you decided to use **full preset with Authentication**, you also need to:
   1. Configure your favorite database (mysql, sqlite, etc.),
   2. Run `php artisan migrate` to create basic user tables in the database,
7. Run `php artisan serve` (or equivalent) to run server and check the result.

## Dependencies

Those front-end dependencies will be added to the NPM project during preset installation:

* [foundation-sites](https://www.npmjs.com/package/foundation-sites) **^6.6**,
* [jquery](https://www.npmjs.com/package/jquery) **^3.5**.

## Screenshots

![Foundation login screen](/screenshots/foundation_login_screen.png)

## License

MIT
