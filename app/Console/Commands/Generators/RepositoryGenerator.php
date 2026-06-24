<?php

namespace App\Console\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use App\Console\Commands\Stubs\RepositoryInterfaceStubGenerator;
use App\Console\Commands\Stubs\RepositoryStubGenerator;

class RepositoryGenerator
{
    private Filesystem $filesystem;
    private RepositoryInterfaceStubGenerator $interfaceStubGenerator;
    private RepositoryStubGenerator $repositoryStubGenerator;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->interfaceStubGenerator = new RepositoryInterfaceStubGenerator();
        $this->repositoryStubGenerator = new RepositoryStubGenerator();
    }

    public function generate(string $name, callable $callback): void
    {
        $repositoryDir = app_path("Repositories/{$name}");
        $interfacePath = "$repositoryDir/{$name}RepositoryInterface.php";
        $repositoryPath = "$repositoryDir/{$name}Repository.php";

        $this->filesystem->ensureDirectoryExists($repositoryDir);

        // Generate Interface
        if (!$this->filesystem->exists($interfacePath)) {
            $this->filesystem->put($interfacePath, $this->interfaceStubGenerator->generate($name));
            $callback("Repository Interface created: {$interfacePath}", 'info');
        } else {
            $callback("Repository Interface already exists: {$interfacePath}", 'warn');
        }

        // Generate Repository
        if (!$this->filesystem->exists($repositoryPath)) {
            $this->filesystem->put($repositoryPath, $this->repositoryStubGenerator->generate($name));
            $callback("Repository created: {$repositoryPath}", 'info');
        } else {
            $callback("Repository already exists: {$repositoryPath}", 'warn');
        }
    }
}
