# Laravel Migration Manager

Fixes an annoying problem that Laravel doesn't fix - The ability to organize migrations into different folders.

Note: you can't have .php files in the root of the migration path. Everything has to be ordered in folders, otherwise Laravel's migrate:reset won't work.

## Install

Via Composer

``` bash
$ composer require tomlegkov/migration-manager
```

And then run dump-autoload to configure the classes
``` bash
$ composer dump-autoload
```

## Usage

``` bash
# Create a new migration, just as you would normally
$ php artisan make:model TestModel --migration

# Now, move it to a folder with Migration Manager
# It will automatically find the last created file
$ php artisan migman:move folderName

# Now you're ready to migrate
$ php artisan migman

# And now you can rollback aswell!
$ php artisan migman:reset
```

## Credits

- Tom Legkov https://github.com/tomlegkov 

## License

Apache License 2.0. Please see [License File](LICENSE.md) for more information.
