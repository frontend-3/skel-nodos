ZfEloquent
==========
This is a Zend Framework 2.4.9 module for bootstrapping the [Eloquent ORM](https://laravel.com/docs/5.1/eloquent).

**Author:** Jaime G. Wong <<jaime.wong@nodosdigital.pe>>  
**Version:** 1.0.0  
**illuminate/database version:** 5.2.*

### Configuring
Add `illuminate/database` to your `composer.json` file's `require` section:

    "illuminate/database": "5.2.*"

Add this entry to your `config/autoload/local.php` file:

    'eloquent' => array(
        'host'      => '127.0.0.1',
        'database'  => '',
        'username'  => '',
        'password'  => '',
        'driver'    => 'mysql',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ),


