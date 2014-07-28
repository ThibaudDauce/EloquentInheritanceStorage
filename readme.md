Eloquent Inheritance Storage
===============
[![Build Status](https://travis-ci.org/ThibaudDauce/EloquentInheritanceStorage.svg)](https://travis-ci.org/ThibaudDauce/EloquentInheritanceStorage)

## Introduction

Eloquent Inheritance Storage is extending Eloquent ORM in order to provide support for models extending other models. It allows you to store and retrieve parent and child models easily.

This package is an extension of `single table inheritance` pattern. It uses views instead of tables to store data, and, this way, avoids tables with many NULL values.

## Installation
[PHP](https://php.net) 5.5+ and [Laravel](http://laravel.com) 4.2+ are required.

To get the latest version of Eloquent Inheritance Storage, simply require `"thibaud-dauce/eloquent-inheritance-storage": "0.*"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Eloquent Inheritance Storage is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'ThibaudDauce\EloquentInheritanceStorage\EloquentInheritanceStorageServiceProvider'`

You can register the `InheritanceStorage` facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'InheritanceStorage' => 'ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage'`

## Model configuration example

### Presentation

I'm currently developing a video game with characters. I also need warriors with rage and wizards with magic.

* `Character`: id, name.
  * `Warrior` extends `Character`: id, name, rage.
  * `Wizard` extends `Character`: id, name, magic.

### Models

Apply the `ThibaudDauce\EloquentInheritanceStorage\ParentTrait` to the `Character` model.

```php
<?php

use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;

class Character extends Eloquent {

  use ParentTrait;

  $table = 'characters';
  $primaryKey = 'name';
}
```

Don't do anything to the `Warrior` and `Wizard` models.

```php
<?php

class Warrior extends Character {

  $table = 'warriors';
}

class Wizard extends Character {

  $table = 'wizards';
}
```

### Database

Create regular tables for child models.
```php
<?php

Schema::create('warriors', function(Blueprint $table)
{
  $table->increments('id');
  $table->string('name')->unique();
  $table->integer('rage');
});

Schema::create('wizards', function(Blueprint $table)
{
  $table->increments('id');
  $table->string('name')->unique();
  $table->integer('magic');
});
```

Name the `Character` table `characters_storage`.
```php
<?php

Schema::create('characters_storage', function(Blueprint $table)
{
  $table->increments('id');
  $table->string('name')->unique();
});
```

And finally, create the `characters` view: union of all the tables. Don't forget to add `class_name` field.
```php
<?php

DB::statement('
CREATE VIEW `characters` AS
  SELECT
    `characters_storage`.`id` AS `id` ,
    \'Character\' AS `class_name` ,
    `characters_storage`.`name` AS `name` ,
    NULL AS `rage` ,
    NULL AS `magic` ,
  FROM `characters_storage`
  UNION
  SELECT
    `warriors`.`id` AS `id` ,
    \'Warrior\' AS `class_name` ,
    `warriors`.`name` AS `name` ,
    `warriors`.`rage` AS `rage` ,
    NULL AS `magic` ,
  FROM `warriors`
  UNION
  SELECT
    `wizards`.`id` AS `id` ,
    \'Wizard\' AS `class_name` ,
    `wizards`.`name` AS `name` ,
    `wizards`.`magic` AS `magic` ,
    NULL AS `rage` ,
  FROM `wizards` ;
');
```

## Usage

### Get model

`Character::all()` will return a collection with `Character`, `Warrior` and `Wizard` models.

`Character::find($characterName)` will return a `Character`.

`Character::find($warriorName)` will return a `Warrior`.

`Warrior::find($warriorName)` will return a `Warrior`.

`Warrior::find($characterName)` or `Warrior::find($wizardName)` will throw an error.

### Save model

`Character::create(array('name' => 'Thibaud'))` will add a line in `characters_storage` table.

`Warrior::create(array('name' => 'Thibaud', 'rage' => 10))` will add a line in `warriors` table.

## Extending the package

### Storage name

You can use a different storage table name by defining `$inheritanceStorageName` in your parent model.

Example: for a view named `characters` and a storage table named `characters-table`
```php
<?php

use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;

class Character extends Eloquent {

  use ParentTrait;

  $table = 'characters';
  $inheritanceStorageName = 'characters-table';
  $primaryKey = 'name';
}
```
