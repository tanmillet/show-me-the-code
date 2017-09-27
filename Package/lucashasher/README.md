[![Build Status](https://travis-ci.org/tanmillet/lucashasher.svg?branch=master)](https://travis-ci.org/tanmillet/lucashasher) [![StyleCI](https://styleci.io/repos/97816897/shield?branch=master)](https://styleci.io/repos/97816897)
# Installation
Require this package with composer:

> composer require terrylucas/md5hasher dev-master

After updating composer, add the ServiceProvider to the providers array in config/app.php

> Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider

Laravel 5.x:

```php

TerryLucas2017\Hasher\LucasMD5Provider::class,

```

# Usage

```php

    $hashValue = app('lucasmd5')->make('123456');

    $isEqual = app('lucasmd5')->check('123456' , $hashValue);

    $hashValue = app('lucasmd5')->make('123456' , ['salt' => 'terry']);

    $isEqual = app('lucasmd5')->check('123456' , $hashValue , ['salt' => 'terry']);
    
```
