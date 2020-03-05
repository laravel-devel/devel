Devel is a modular Laravel platform which comes with an admin dashboard and a CRUD generator.

The modularity was achieved with the help of the [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) package, which was modified to the needs of Devel.

Devel is not a CMS and is meant to make the life of developers a bit easier.

**PROJECT STATUS: NOT READY TO BE USED!** The project is in its earlier stage. The dashboard/CRUD is incomplete and there are no any ready-to-use modules yet.

## Features
- Modular structure - organize your code into reusable modules, use modules made by other people
- The Devel Core and Devel Dashboard are modules too. Feel free to modify them to your needs.
- User roles and permissions
- An admin dashboard built with custom Vue.js components
- CRUD generation for the dashboard

## Installation

- Run `git clone https://github.com/voerro/devel.git your-project-name` (`your-project-name` is a folder name to clone the project to)
- Navigate to your project folder
- Run `cp .env.example .env` or manually create the `.env` file and copy over the contents of `.env.example`
- Edit the DB settings in `.env`
- Run `composer install`
- Run `php artisan devel:install`
- Now you can use Devel. To access the dashboard go to `/dashboard` and log in under `root@example.com` / `qwerty`. These credentials can be changed on the dashboard.

## Contributing

Thank you for considering contributing to Devel! To make a contribution just fork the project and create a Pull Request. Please point your PRs to be merged into the `dev` branch.

## License

Devel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
