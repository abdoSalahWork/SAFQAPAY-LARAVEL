<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class MakeInterfaceCommand extends Command
{

    protected $signature = 'make:interface {name}';


    protected $description = 'Make an Interface Class';

    protected $files;
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }


    public function handle()
    {
        $path = $this->getSourceFilePath();

        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->info("File : {$path} already exits");
        }

    }
    public function getStubPath()
    {
        return __DIR__ . '/../../../stubs/interface.stub';
    }

    public function getStubVariables()
    {
        return [
            'NAMESPACE'         => 'App\\Http\\Interfaces',
            'CLASS_NAME'        => $this->getSingularClassName($this->argument('name')),
        ];
    }
    public function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('$'.$search.'$' , $replace, $contents);
        }

        return $contents;

    }

    public function getSourceFilePath()
    {
        return base_path('App\\Http\\Interfaces') .'\\' .$this->getSingularClassName($this->argument('name')) . 'Interface.php';
    }

    public function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
