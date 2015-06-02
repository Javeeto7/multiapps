# Multiapps for Laravel 5

Simple Laravel 5 package for handling access to child-applications within a main-application.
Main App
-->App1
-->App2
...
## Installation

Pull this package  through Composer. composer.json

```js
{
    "require": {
        "reivaj86/appls": "dev-master"
    }
}
```
Run
    $ composer update

Add the package to your application service providers in: `config/app.php`

```php
'providers' => [

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...

    'Reivaj86\Multiapps\MultiappsServiceProvider',

],
```

Publish the package migrations.
Publish config file to your application.

    $ php artisan vendor:publish --provider="Vendor/Reivaj86/Multiapps/MultiappsServiceProvider" --tag="config"
    $ php artisan vendor:publish --provider="Vendor/Reivaj86/Multiapps/MultiappsServiceProvider" --tag="migrations"


Run migrations. 

    $ php artisan migrate

Crate your seeds // optional
Run the seeder

---- Configuration file ---- config.php

You can change the connection for your models, slug separator and there is also a very userfull pretend option. View the config file for more information.

---- Usage ---- IsApplUser trait & IsApplUserContract

First of all, include `IsApplUser` trait and also implement `IsApplUserContract` inside your `User`model or `Custom` model.

```php
use Reivaj86\Multiapps\Contracts\IsApplUserContract;
use Reivaj86\Multiapps\Traits\IsApplUser;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, IsApplUserContract {

	use Authenticatable, CanResetPassword, IsUserAppl;
```

Done!. You can create your first appl and attach it to a User or Custom Model

```php
use Reivaj86\Multiapps\Models\Appl;
use App\User;

$appl = Appl::create([
    'name' => 'Child_App_Name',
    'slug' => 'child_app_slug',
    'description' => '' // optional
]);

$user = User::find($id)->attachAppl($appl); // you can pass whole object, or just id
```

You can easily check if the current user uses a child_app.

```php
if ($user->can('child_app')) // you can pass an id or slug
{
    return 'child_app_slug';
}
```

You can also do the following:

```php
if ($user->usesChild_App_Name())
{
    return 'child_app_slug';
}

```

And also, there is a way to check if a User/Model has access to multiple appls:

```php
if ($user->can('child_app_1|child_app_2')) // or $user->can('child_app_1, child_app_2') and also $user->can(['child_app_1', 'child_app_2'])
{
    // if user has at least one appl
}

if ($user->can('child_app_1|child_app_2', 'All')) // or $user->can('child_app_1, child_app_2', 'All') and also $user->can(['child_app_1', 'child_app_2'], 'All')
{
    // if user has all appls
}
```

When you are creating appls, there is also optional parameter `level`. It is set to `1` by default, but you can overwrite it and then you can do something like this:

```php
if ($user->level() > 3)
{
    // code
}
```
This option is very usefull when you want to set access levels to every app. You can easily integrate this with a Role and level authentication package.
If user has multiple appls, the method `level` returns the leve for that appl. For a basic User it shall always be 1. 


--- Blade Extensions ---- @appl & @uses & @allowed

There are three Blade extensions. Basically, it is replacement for classic if statements.

```php
@appl('child_app_slug') // @if(Auth::check() && Auth::user()->uses('child_app_slug'))
    // user can use child_app_slug
@endappl

@allowed('child_app_slug', $view) // @if(Auth::check() && Auth::user()->allowed('child_app', $view))
    // show child_app specific content // Access to specific content within the child_app
@endallowed

@appl('child_app_1|child_app_2', 'all') // @if(Auth::check() && Auth::user()->can('child_app_1|child_app_2', 'all'))
    // user can use child_app_1 and also child_app_2
@else
    // something else
@endappl
```

For more information, please have a look at [IsApplUserContract](https://github.com/reivaj86/multiapps/blob/master/src/Reivaj86/Multiapps/Contracts/IsApplUserContract.php).

