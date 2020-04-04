<p align="center">
<a href="https://packagist.org/packages/devel/devel"><img src="https://poser.pugx.org/devel/devel/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/devel/devel"><img src="https://poser.pugx.org/devel/devel/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/devel/devel"><img src="https://poser.pugx.org/devel/devel/license.svg" alt="License"></a>
</p>

Devel (a **DEV**eloper's Larav**EL**) enhances Laravel in different ways making life of Laravel developers easier.

The Devel's main goal is to solve some of the common problems, minimize the routine of the development process, give projects a better structure. It comes with an admin dashboard too!

- [Documentation](http://voerro.com/en/projects/devel/)
- [Live Demo](http://devel.voerro.com/dashboard)
    - `admin@example.com` / `qwerty123` - note that this user only has view/read permissions on the demo site and some features won't be visible at all

## Features

This it what Devel adds to a Laravel project:
- Modularity. Organize your code into reusable modules. Install modules made by other people.
- User roles and permissions system.
- A highly customizable admin dashboard module built with Element UI and custom Vue.js components. Comes with a CRUD generator (an artisan command).
- A set of tools and utilities solving some common problems

## The Vision

Programming is a tool of creation. It let's you bring your ideas to life, make lives of others better. Unfortunately, the actual developing process often ends up being repetitive, boring and demotivating. If you've developed multiple websites or apps - you already know this.

It doesn't matter if you're developing a standard website, a web app, an SPA, a mobile app, a PWA, an API, or whatever it is. All of these usually require similar things in its core - a dashboard to manage content; if it's a website - some SEO, possibly a user roles and permissions system, possibly internationalization, possibly even more.

### The Dashboard

A dashboard is usually a collection of identically looking pages with all these endles datatables with pagination/sorting/filtering/search functionality and create/edit forms. The more entities your app has, the more mundane the developing process is.

Devel does its best to take all the pain out. There's a CRUD generator with a bunch of useful traits. If you have something more typical - you'll only have to specify the list of fields you need in your datatable and forms.

If you need something more complicated for your CRUD - Devel doesn't restrict you in any way. A dashboard page doesn't have to be a CRUD page either, it could literally be anything.

### Modularity

Devel gives your Laravel project modularity. For example, if you have a Blog entity/feature in a typical Laravel project, you end up having its code scattered all over the place - controllers under "app/Http/Controllers/Blog", models under "app/Models/Blog", views under "resources/views/blog", and so on. With modules you have it all in one place under "Modules/Blog/...", including the migrations and everything else.

Not only this gives your project a better structure, it also makes parts of your code reusable across multiple projects. You can even share your modules with others and use modules created by others. Devel modules can be distributed and installed via composer like any other package.

### ... and more

While the main focus of Devel is on the dashboard/backend side of things, there are some utils meant to be used in the public part of your website too (if you have a standard website). You can also use some of the code (base classes and traits) used in the dashboard.

## Project Status

**ALPHA RELEASE. USE AT YOUR OWN RISK!** The project is in its earlier development stage. Bugs are possible and breaking changes are made quite often. New features are being added too.

## License

Devel is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
