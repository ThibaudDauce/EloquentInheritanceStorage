Eloquent Inheritance Storage
===============

[![Build Status](https://img.shields.io/travis/ThibaudDauce/EloquentInheritanceStorage/master.svg?style=flat)](https://travis-ci.org/ThibaudDauce/EloquentInheritanceStorage)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)

## Introduction

Eloquent Inheritance Storage is extending Eloquent ORM in order to provide support for models extending other models. It allows you to store and retrieve parent and child models easily.

This package is an extension of the `single table inheritance` pattern. It uses views to combine data coming from several tables of a class hierarchy. By doing this we are avoiding tables with many NULL values.

## Installation
[PHP](https://php.net) 5.4+ and [Laravel](http://laravel.com) 4.2+ are required.

To get the latest version of Eloquent Inheritance Storage, simply require `"thibaud-dauce/eloquent-inheritance-storage": "0.*"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Eloquent Inheritance Storage is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'ThibaudDauce\EloquentInheritanceStorage\EloquentInheritanceStorageServiceProvider'`

You can register the `InheritanceStorage` facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'InheritanceStorage' => 'ThibaudDauce\EloquentInheritanceStorage\Facades\InheritanceStorage'`

## Model configuration example

### Presentation

Let's imagine that I'm currently developing a video game with different kind of characters. I will have some basic characters and some specialized ones:
  * A `warrior` will be a character with a `rage` attribute.
  * A `wizard` will be a character with a `magic` attribute.

My class hierarchy will be the following:
* `Character`: id, name.
  * `Warrior` extends `Character`: id, name, rage.
  * `Wizard` extends `Character`: id, name, magic.

### Models

Apply the `ThibaudDauce\EloquentInheritanceStorage\ParentTrait` to the `Character` model (the parent class).

```php
<?php

use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;

class Character extends Eloquent {

  use ParentTrait;

  protected $table = 'characters';
  protected $primaryKey = 'name';
}
```

Don't do anything to the `Warrior` and `Wizard` models (the child classes).

```php
<?php

class Warrior extends Character {

  protected $table = 'warriors';
}

class Wizard extends Character {

  protected $table = 'wizards';
}
```

### Database

We are going to create 3 tables and a view:
  * a table named `characters_storage` that will contain only basic characters (from the parent class).
  * a table named `warriors` that will contain only warriors (from a child class).
  * a table named `wizards` that will contain only wizards (from a child class).
  * a view named `characters` that will contain characters, warriors and wizards.

Let's create our tables. Pay attention to the different tables' name!
```php
<?php

// Table for our parent class
Schema::create('characters_storage', function(Blueprint $table)
{
  $table->increments('id');
  $table->string('name')->unique();
});

// Tables for our child classes
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

And finally, let's create the `characters` view that will contain our characters, warriors and wizards. Don't forget to the add a `class_name` field to your view.
```php
<?php

DB::statement("
CREATE VIEW `characters` AS
  SELECT
    `characters_storage`.`id` AS `id` ,
    'Character' AS `class_name` ,
    `characters_storage`.`name` AS `name` ,
    NULL AS `rage` ,
    NULL AS `magic` ,
  FROM `characters_storage`
  UNION
  SELECT
    `warriors`.`id` AS `id` ,
    'Warrior' AS `class_name` ,
    `warriors`.`name` AS `name` ,
    `warriors`.`rage` AS `rage` ,
    NULL AS `magic` ,
  FROM `warriors`
  UNION
  SELECT
    `wizards`.`id` AS `id` ,
    'Wizard' AS `class_name` ,
    `wizards`.`name` AS `name` ,
    `wizards`.`magic` AS `magic` ,
    NULL AS `rage` ,
  FROM `wizards` ;
");
```

## Usage

### Get a model

`Character::all()` will return a collection containing `Character`, `Warrior` and `Wizard` models.

`Character::find($characterName)` will return a `Character`.

`Character::find($warriorName)` will return a `Warrior`.

`Warrior::find($warriorName)` will return a `Warrior`.

`Warrior::find($characterName)` or `Warrior::find($wizardName)` will throw an error.

### Save a model

`Character::create(array('name' => 'Thibaud'))` will store a character in the `characters_storage` table.

`Warrior::create(array('name' => 'Thibaud', 'rage' => 10))` will add a line in the `warriors` table.

## Extending the package

### Storage name

You can use a different storage table name by defining `$inheritanceStorageName` in your parent model.

Example: for a view named `characters` and a storage table named `characters-table`
```php
<?php

use ThibaudDauce\EloquentInheritanceStorage\ParentTrait;

class Character extends Eloquent {

  use ParentTrait;

  protected $table = 'characters';
  protected $inheritanceStorageName = 'characters-table';
  protected $primaryKey = 'name';
}
```