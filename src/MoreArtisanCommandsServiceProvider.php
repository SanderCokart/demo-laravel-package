<?php

namespace SanderCokart\MoreArtisanCommands;

use Illuminate\Support\ServiceProvider;
use SanderCokart\MoreArtisanCommands\Console\Commands\EnumMakeCommand;
use SanderCokart\MoreArtisanCommands\Console\Commands\RemoveDocComments;
use SanderCokart\MoreArtisanCommands\Console\Commands\EnumerateConfigCommand;
use SanderCokart\MoreArtisanCommands\Console\Commands\TraitMakeCommand;

class MoreArtisanCommandsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    
    }
    
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/stubs/enum.stub'        => base_path('stubs/enum.stub'),
            __DIR__ . '/stubs/config-enum.stub' => base_path('stubs/enum-config.stub'),
            __DIR__ . '/stubs/trait.stub'       => base_path('stubs/trait.stub'),
        ], 'stubs');
        
        if ($this->app->runningInConsole()) {
            $this->commands([
                EnumerateConfigCommand::class,
                RemoveDocComments::class,
                EnumMakeCommand::class,
                TraitMakeCommand::class,
            ]);
        }
    }
}
