<?php

namespace SanderCokart\MoreArtisanCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class TraitMakeCommand extends GeneratorCommand
{
    
    protected $signature = 'make:trait {name}';
    protected $description = 'Create a new trait';
    protected $type = 'Trait';
    
    protected function getStub(): string
    {
        return base_path('stubs/trait.stub');
    }
    
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Traits';
    }
    
    protected function replaceClass($stub, $name): array|string
    {
        return str_replace('{{trait_name}}', Str::studly($this->argument('name')), $stub);
    }
}
