# What is this project?

One day I realized I have no application for managing my home budget. I've looked at few, but sadly none of them seemed to match what I expected from such application and that's why I began to roll my own one.

Using my application, you can thoroughly prepare and manage your home budget.
What I mean by this is that you can create your own budget (let's say `Joe's Budget`) and assign it transactions.

A transaction is either an `income` or an `expense` with some value (which can be constant - like `$500` - or ranged - like `$500-$750`).
Each transaction has its periodicity - it can be one-shot (eg. you bought a TV), daily (eg. work food) or weekly/monthly/yearly (rents). Having prepared the transactions, the application is now able to compute how much money you're spending on each one, with how much money you'll be left at the end of the month etc.

If it seems really simple, it actually is ;-)
But remember: I'm constantly adding new features - today it's only a toy, but after a year - who knows? Probably it'll still be a toy because I'm kinda lazy, but hey - at least a my own one! :-)

# What languages/currencies are supported?

Application is built with multilingualism and multi-currency in mind, but the only language built-in for now is Polish. Before releasing some alpha/beta version I'll certainly translate it to English but right now it is as it is.

# How do I install it?

I'm assuming you already know how to setup a local/remote site.
If no, please read a tutorial appropriate for your OS/distribution/etc. 

## Software requirements

1. Apache 2 *(you could technically use `nginx`, but I haven't tested this combination)*,

1. PHP 7.1,

3. MySQL / MariaDB + *optionally* Redis,

4. Git,

5. npm.

## Steps to install a development version

1. `git clone` this repository. 

2. Set appropriate permissions: `chmod 777 -R storage boostrap/cache`.

3. Prepare your Apache / nginx to point at `/wherever-you-cloned-this-repository/public`. `htaccess` support is required.

4. Install the dependencies:

    1. `npm install`,
    
    2. `composer install`,
    
5. Compile the assets: `npm run prod`.

6. Create a database schema in MySQL / MariaDB.

7. Copy `.env.example` to `.env` and set appropriate database/Redis connection data.

8. Generate application key using `php artisan key:generate`.

9. You should now be able to open the application - it should show a maintenance page with an "application's not yet installed" message. If you see this - good. If you happen not to - check all the permissions, take a look at logs etc.

10. Run `php artisan migrate --seed`, wait for the application to prepare the database.

11. You may now sign in using the common `admin` / `admin` credentials :-)  