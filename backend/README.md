skelsus-zend-site Backend Application
====================================

Installation instructions
-------------------------

*Edit config/autoload/local.php*

* Configure name, username and password of the database.

* Configure the 'site' variables for development or production stage.

* Configure the e-mail account for sending e-mails.

*Initialize the database*

This application uses Phinx as the database migrations handler.

On the backend directory, run `vendor/bin/phinx init`

Edit the file `phinx.yml` and configure the database connection for Phinx.

Finally, run `vendor/bin/phinx migrate`

*Initialize the Admin*

To create an Admin Super User, run the following command and provide a password:

`php public/index.php createsuperuser`

To update the Permissions of the newly created Super User run:

`php public/index.php updatepermsuperuser`


Launching the PHP Web Server (Development only)
-----------------------------------------------

Use this command to launch the PHP Web Server. On the backend directory run:

`php -S 0.0.0.0:8080 -t public public/index.php`

Replace the port 8080 for any other if it's already used.

