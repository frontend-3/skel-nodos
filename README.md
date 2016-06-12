skelsus-zend-site Application
=============================

Introduction
------------
This is a skeleton for a app/website made in ZF2


Basic Requirements:
------------------
1. PHP >=5.5
2. MySQL
3. GD
4. php_intl
5. php_fileinfo (get mimetypes for images)


Install Composer
----------------
The recommended way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --

You would then invoke `composer` to install dependencies
`composer.phar`:

    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)


Create a new MySQL Database
---------------------------
Using the MySQL console or your preferred interface, create a new MySQL database, user and password. Take note of these.


Initializing the Database
-------------------------
This application uses Phinx as the database migrations handler. Execute the following commands:

    cd backend/
    vendor\bin\phinx init .

This will create a file name `phinx.yml`. Edit this file with the database connection credentials on the `development` or `production` section as appropiate.

After this, run:

    vendor\bin\phinx migrate -e [enviroment]


Local Settings
--------------
In config/autoload there is a sample file called `local.txt`, rename it to `local.php`.

1. Configure name, username and password of the database.
2. Configure the 'site' variables for development or production stage.
3. Configure the e-mail account for sending e-mails.


Initializing the Admin Super User
---------------------------------
To create an administration section Super User, run the following command from the root of the backend directory and provide a password:

    php public/index.php createsuperuser

By default, the username is `admin`.

Run the following command to populate the permissions table and grant them to the Super User.

    php public/index.php updatepermsuperuser



Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP CLI server in the backend root directory:

    php -S 0.0.0.0:8080 -t public/index.php

This will start the server on port 8080, and bind it to all network interfaces.

**Note:** This built-in server is *for development only*.

