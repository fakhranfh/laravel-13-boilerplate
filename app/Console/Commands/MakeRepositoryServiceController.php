<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\Generators\RepositoryGenerator;
use App\Console\Commands\Generators\ServiceGenerator;
use App\Console\Commands\Generators\ControllerGenerator;
use App\Console\Commands\Generators\FormRequestGenerator;
use App\Console\Commands\Generators\RouteGenerator;
use App\Console\Commands\Generators\ServiceProviderBindingGenerator;
use App\Console\Commands\Generators\BladeGenerator;

class MakeRepositoryServiceController extends Command
{
    protected $signature = 'make:rsc {name} {--label=} {--view-path=} {--repository-service-only}';
    protected $description = 'Generate Repository, Service, and Controller for a given name';

    public function handle(): int
    {
        $name = $this->argument('name');
        $label = $this->option('label') ?: $name;
        $viewPath = $this->option('view-path') ?: 'app';

        $this->generateRepository($name);
        $this->generateService($name, $label);
        $this->bindToServiceProvider($name);

        if ($this->option('repository-service-only')) {
            return 0;
        }

        $this->generateController($name, $label, $viewPath);
        $this->generateFormRequests($name);
        $this->generateBladeViews($name, $label, $viewPath);
        $this->generateRoutes($name, $label);

        Artisan::call('optimize');
        $this->info('php artisan optimize executed.');

        return 0;
    }

    private function generateRepository(string $name): void
    {
        $generator = new RepositoryGenerator();
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateService(string $name, string $label): void
    {
        $generator = new ServiceGenerator();
        $generator->generate($name, $label, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateController(string $name, string $label, string $viewPath = 'app'): void
    {
        $generator = new ControllerGenerator();
        $generator->generate($name, $label, $viewPath, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateFormRequests(string $name): void
    {
        $generator = new FormRequestGenerator();
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateRoutes(string $name, string $label): void
    {
        $generator = new RouteGenerator();
        $generator->generate($name, $label, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function bindToServiceProvider(string $name): void
    {
        $generator = new ServiceProviderBindingGenerator();
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateBladeViews(string $name, string $label, string $viewPath): void
    {
        $generator = new BladeGenerator();
        $generator->generate($name, $label, $viewPath, function (string $message, string $type) {
            $this->$type($message);
        });
    }
}
