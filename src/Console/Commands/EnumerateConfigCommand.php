<?php

namespace SanderCokart\MoreArtisanCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EnumerateConfigCommand extends Command
{
    protected $signature = 'config:enumerate {fileName}';
    protected $description = 'Generate enums for config files';
    
    public function handle(): void
    {
        //check if stub file is present in stubs folder
        
        $fileName = $this->argument('fileName');
        $stub = $this->getStubFile();
        $config = config($fileName);
        
        $enumName = Str::studly($fileName);
        $dotNotatedConfig = $this->generateDotNotatedArray($config);
        $enumCasesString = $this->generateEnumCasesString($dotNotatedConfig);
        
        $stub = $this->replaceClass($stub, ($enumName . 'ConfigEnum'), $enumCasesString);
        $stub = $this->replaceValues($stub, $enumCasesString);
        
        $this->createConfigEnumFile($enumName, $stub);
        
        $this->info("Enum $enumName created successfully.");
    }
    
    protected function generateEnumCasesString($dotNotatedArray): string
    {
        $enumCases = '';
        foreach ($dotNotatedArray as $key => $value) {
            $enumCases .= '    case ' . Str::studly($key) . ' = \'' . $value . "';\r\n";
        }
        
        return $enumCases;
    }
    
    protected function generateDotNotatedArray(array $config): array
    {
        $result = [];
        
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $result[Str::studly($key . '_' . $key2 . '_' . $key3)] = $key . '.' . $key2 . '.' . $key3;
                        }
                    } else {
                        $result[Str::studly($key . '_' . $key2)] = $key . '.' . $key2;
                    }
                }
            } else {
                $result[Str::studly($key)] = $key;
            }
        }
        
        return $result;
    }
    
    protected function replaceClass(string $stub, string $enumName, $enumCasesString): string
    {
        return str_replace('{{enum_name}}', $enumName, $stub);
    }
    
    protected function replaceValues(string $stub, $enumCasesString): string
    {
        return str_replace('{{enum_values}}', $enumCasesString, $stub);
    }
    
    protected function createConfigEnumFile(string $enumName, string $stub): void
    {
        $enumFile = app_path('Enums/' . $enumName . 'ConfigEnum.php');
        file_put_contents($enumFile, $stub);
    }
    
    protected function resolveStubPath(string $stub): string
    {
          return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
              ? $customPath
              : __DIR__.'/../../stubs/'.$stub;
    }
    
    protected function getStubFile(): bool|string
    {
        return file_get_contents($this->resolveStubPath('config-enum.stub'));
    }
}
