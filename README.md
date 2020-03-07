<p align="center">
<a href="https://packagist.org/packages/voerro/devel"><img src="https://poser.pugx.org/voerro/devel/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/voerro/devel"><img src="https://poser.pugx.org/voerro/devel/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/voerro/devel"><img src="https://poser.pugx.org/voerro/devel/license.svg" alt="License"></a>
</p>

Devel is a modular Laravel platform which comes with an admin dashboard and a CRUD generator.

The modularity was achieved with the help of the [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) package, which was modified to the needs of Devel.

Devel is not a CMS and is meant to make the life of developers a bit easier.

**PROJECT STATUS: NOT READY TO BE USED!** The project is in its earlier stage. The dashboard/CRUD is incomplete and there are no any ready-to-use modules yet. Besides, breaking changes are made all the time.

## Features
- Modular structure - organize your code into reusable modules, use modules made by other people
- The Devel Core and Devel Dashboard are modules too. Feel free to modify them to your needs.
- User roles and permissions
- An admin dashboard built with custom Vue.js components
- CRUD generation for the dashboard

## Installation

- Run `composer create-project --remove-vcs voerro/devel your-project-name dev-master` (`your-project-name` is a folder name to install the project to)
- Navigate to your project folder
- Edit the DB settings in `.env`
- Run `php artisan devel:install`
- Now you can use Devel. To access the dashboard go to `/dashboard` and log in under `root@example.com` / `qwerty`. These credentials can be changed on the dashboard.

## Contributing

Thank you for considering contributing to Devel! To make a contribution just fork the project and create a Pull Request. Please point your PRs to be merged into the `dev` branch.

## License

Devel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
