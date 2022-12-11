<?php

namespace SanderCokart\MoreArtisanCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class EnumMakeCommand extends GeneratorCommand
{
    
    protected $signature = 'make:enum {name}';
    protected $description = 'Create a new enum';
    protected $type = 'Enum';
    
    protected function getStub(): string
    {
        return base_path('stubs/enum.stub');
    }
    
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Enums';
    }
    
    protected function replaceClass($stub, $name): array|string
    {
        return str_replace('{{enum_name}}', Str::studly($this->argument('name')), $stub);
    }
}
