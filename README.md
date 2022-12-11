<h1 align="center"><a style="color:#F05340;" href="https://github.com/SanderCokart/more-artisan-commands" target="_blank">More Artisan Commands</a></h1>

## About More Artisan Commands
While some people might have access to PHPStorm and have the luxury of using the `Settings > PHP > Laravel > Enable plugin for this project` some people don't.

For Those people I have created this package. Its first goal was to enumerate all the config files in the config folder and create an enum for each one of them config keys and values.

This way you can use the IDE's autocomplete to find the config key you are looking for.

But then I thought, this is my first package lets add some novelty stuff in there. So I added another command to remove phpdoc comments from files and commands to create traits and enums.

Feel free to do Pull Requests to add more novelty commands.

## Installation
You can install the package via composer:

```bash
composer require sandercokart/more-artisan-commands --dev
```

## Vendor Publish
### Stubs
This package comes with some stubs for the `make:command` command. These stubs are published to the `stubs` folder in the root of your project. You can publish these stubs with the following command:

```bash
php artisan vendor:publish --provider="SanderCokart\MoreArtisanCommands\MoreArtisanCommandsServiceProvider" --tag=stubs
```

## Added Commands
### Make Commands
* #### ```make:trait {name}```
    Creates a new trait in the `app/Traits` folder.
* #### `make:enum {name}`
    Creates a new enum in the `app/Enums` folder.
### Config Commands
* #### `config:enumerate`
    Creates a new enum in the `app/Enums` folder where each case is a key from the config file.