<?php

namespace App\Console\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use App\Console\Commands\Stubs\ControllerStubGenerator;

class ControllerGenerator
{
    private Filesystem $filesystem;
    private ControllerStubGenerator $stubGenerator;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->stubGenerator = new ControllerStubGenerator();
    }

    public function generate(string $name, string $label, string $viewPath, callable $callback): void
    {
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");

        $this->filesystem->ensureDirectoryExists(app_path('Http/Controllers'));

        if (!$this->filesystem->exists($controllerPath)) {
            $this->filesystem->put($controllerPath, $this->stubGenerator->generate($name, $label, $viewPath));
            $callback("Controller created: {$controllerPath}", 'info');
        } else {
            $callback("Controller already exists: {$controllerPath}", 'warn');
        }
    }
}
