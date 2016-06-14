SKELSUS
=======
Skeleton project for fun and profit.

Copyright (C) Nodos Digital  


Craftsmen:
----------
- César Mandamiento
- Eduardo Flores
- Elizabeth Manrique
- Jaime Wong
- Jorge Torres
- Luis Valdivia
- Pedro Herrera
- Raúl Santamaria
- Ronald Martínez


Basic Requirements:
------------------
1. PHP >= 5.5.32
2. MySQL >= 5.6.21
3. GD
4. php_intl
5. php_fileinfo


Install Composer
----------------
The recommended way to get a working copy of this project is to clone the repository and use **[Composer](https://getcomposer.org)** to install the dependencies.

To install Composer:

    curl -s https://getcomposer.org/installer | php --

Invoke `composer.phar` to install the dependencies:

    php composer.phar self-update
    php composer.phar install

The `self-update` directive is to ensure that your Composer copy is up-to-date.

#### Production
On a Production environment, use this installing command instead:

    php composer.phar install --no-dev

This flag tells Composer not to be install extra development libraries or modules, such as Unit Testing and Documentation.


Configuration
-------------
In the `backend/config/autoload` directory there is a sample configuration file called `local.php.dist`, rename it to `local.php`.

- If the application will be installed in a different path rather than the root (i.e. "/"), define it on section `view_manager`, on the key `base_path`. **Important:** The path should end with a `/` character!
- Configure the e-mail account for sending e-mails (if applicable).


Database
--------

Using your preferred interface to MySQL, create a new database, user and password.

In the `backend/config/autoload` directory there is a file called `database.php.dist`, rename it to `database.php`. Configure the database credentials on the proper variables.

This application uses **[Phinx](https://phinx.org)** as the database migrations handler. Phinx is configured to read the database credentials configured in the `database.php` config file, described in the previous section. Once configured, return to the `backend` directory and execute this command:

    vendor\bin\phinx migrate

Phinx will create and, if applicable, fill the database with initial data required by the application.


Production Mode
---------------
This application will run by default in **Development environment.**

To enable **Production environment**, go to the `backend` directory and run the following command:

    php index.php setenv production

To switch back to **Development environment** run:

    php index.php setenv development

To show the current Application environment:

    php index.php setenv status


Administration Interface
------------------------
To create an administration section Super User, run the following command at the `backend` directory and provide a password:

    php public/index.php createsuperuser

By default, the username is `admin`.

Run the following command to populate the permissions table and grant them to the Super User.

    php public/index.php updatepermsuperuser

The Administration section can be accessed at the `/admin` path of the application's URL root (e.g. `http://localhost/admin`).


Web Server
----------

#### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP CLI server in the `backend` directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the server on port 8080, and bind it to all network interfaces.

In a web browser, you can access the application at the URL `http://localhost:8080`

**Note:** The built-in server should be used for development only.

