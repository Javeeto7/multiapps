# Multiapps for Laravel 5

Simple Laravel 5 package for handling access to child-applications within a main-application.
Main App
-->App1
-->App2
...
## Install

Pull this package in through Composer.

```js
{
    "require": {
        "reivaj86/appls": "1.0.*"
    }
}
```

    $ composer update

Add the package to your application service providers in `config/app.php`

```php
'providers' => [

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...

    'Reivaj86\Multiapps\MultiappsServiceProvider',

],
```

Publish the package migrations and config file to your application.

    $ php artisan vendor:publish --provider="Vendor/Reivaj86/Multiapps/MultiappsServiceProvider" --tag="config"
    $ php artisan vendor:publish --provider="Vendor/Reivaj86/Multiapps/MultiappsServiceProvider" --tag="migrations"

Run migrations.

    $ php artisan migrate

### Configuration file

You can change connection for models, slug separator and there is also a handy pretend feature. Have a look at config file for more information.

## Usage

First of all, include `IsApplUser` trait and also implement `IsApplUserContract` inside your `User` model.

```php
use Reivaj86\Multiapps\Contracts\IsApplUserContract;
use Reivaj86\Multiapps\Traits\IsApplUser;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, HasRoleAndPermissionContract {

	use Authenticatable, CanResetPassword, IsUserAppl;
```

You're set to go. You can create your first appl and attach it to a user.

```php
use Reivaj86\Multiapps\Models\Appl;
use App\User;

$role = Role::create([
    'name' => 'Child_App_Name',
    'slug' => 'child_app_slug',
    'description' => '' // optional
]);

$user = User::find($id)->attachAppl($appl); // you can pass whole object, or just id
```

You can simply check if the current user uses a child_app.

```php
if ($user->can('child_app')) // you can pass an id or slug
{
    return 'child_app_slug';
}
```

You can also do this:

```php
if ($user->usesChild_App_Name())
{
    return 'child_app_slug';
}

```

And of course, there is a way to check for multiple appls:

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
if ($user->level() > 4)
{
    // code
}
```

If user has multiple appls, method `level` returns the permission lever for that appl.


## Blade Extensions

There are three Blade extensions. Basically, it is replacement for classic if statements.

```php
@appl('child_app_slug') // @if(Auth::check() && Auth::user()->can('child_app_slug'))
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

For more information, please have a look at [IsApplUserContract](https://github.com/reivaj86/multiapps/blob/master/src/Reivaj86/Multiapps/Contracts/IsApplUSerContract.php).

