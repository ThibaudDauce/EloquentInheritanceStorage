Eloquent Inheritance Storage
===============


## Introduction

## Installation
[PHP](https://php.net) 5.5+ and [Laravel](http://laravel.com) 4.2+ are required.

To get the latest version of Eloquent Inheritance Storage, simply require `"thibaud-dauce/eloquent-inheritance-storage": "0.*"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Eloquent Inheritance Storage is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'ThibaudDauce\EloquentInheritanceStorage\EloquentInheritanceStorageServiceProvider'`

You can register the `InheritanceStorage` facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'InheritanceStorage' => 'ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage'`

## Model configuration

## Usage
