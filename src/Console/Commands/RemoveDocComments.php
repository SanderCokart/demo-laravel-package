<?php

namespace SanderCokart\MoreArtisanCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveDocComments extends Command
{
    protected $signature = 'sc:remove-doc-comments {path}';
    protected $description = 'Remove doc comments from files';
    
    public function handle(): void
    {
        // Get the path from the command line
        $path = $this->argument('path');
        
        //check if path is file or directory
        $directoryModeActive = is_dir($path);
        
        //ask user if they wish to place the files in a new directory relative to the path provided
        $newDirectoryName = $this->ask('Enter a new directory name to place the files in relative to your target path, or press enter to overwrite the files')
            ?? '';
        
        //ask user for confirmation
        if ($this->confirm('Do you wish to continue?')) {
            //create new directory if user has entered a name relative to the path provided recursively
            if ($newDirectoryName) {
                File::makeDirectory(base_path("{$path}/$newDirectoryName"), recursive: true, force: true);
            }
            
            //process directory of files
            if ($directoryModeActive) {
                $files = File::allFiles($path);
                foreach ($files as $file) {
                    $filePath = $file->getRealPath();
                    $contents = $this->processFile($filePath);
                    $this->handleFile($filePath, $contents, $newDirectoryName);
                }
            } //process single file
            else {
                $contents = $this->processFile($path);
                $this->handleFile($path, $contents, $newDirectoryName);
            }
            $this->info('Done!');
        } else {
            $this->info('Aborted!');
        }
    }
    
    private function handleFile(string $filePath, string $contents, string $newDirectoryName): void
    {
        //put files in new folder
        if ($newDirectoryName) {
            //get directory name from path
            $directoryName = basename(dirname($filePath));
            File::put(base_path("{$directoryName}/$newDirectoryName/") . basename($filePath), $contents);
        } //overwrite files
        else {
            File::put($filePath, $contents);
        }
    }
    
    private function processFile(bool|string $filePath): string
    {
        $contents = File::get($filePath);
        
        //remove phpdoc comments /** */
        $contents = preg_replace('/\/\*\*(.*?)\*\//s', '', $contents);
        
        //remove empty lines
        $contents = preg_replace('/^\h*\v+/m', '', $contents);
        
        //new line after <?php
        $contents = preg_replace('/<\?php/', "<?php\r\r", $contents);
        
        //new line after namespace line
        $contents = preg_replace('/namespace (.*?);/', "namespace $1;\r\r", $contents);
        
        //new line before class line, also account for 'return new class'
        $contents = preg_replace('/^(return new)? class/m', "\r$1 class", $contents);
        
        //remove any empty lines containing //
        $contents = preg_replace('/(\/\/)/m', '', $contents);
        
        //add new line before each public, protected, private method
        $contents = preg_replace('/(public|protected|private)(.*?)(function)/m', "\r\t$1$2$3", $contents);
        
        return $contents;
    }
}
