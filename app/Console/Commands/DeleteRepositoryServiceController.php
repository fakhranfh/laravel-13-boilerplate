<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteRepositoryServiceController extends Command
{
    protected $signature = 'delete:rsc {name} {--migrations}';

    protected $description = 'Delete all generated files for a Repository, Service, and Controller';

    public function handle(): int
    {
        $name = $this->argument('name');
        $kebabName = Str::kebab($name);

        if (! $this->confirm("Delete all files for '{$name}'? This cannot be undone.", false)) {
            $this->info('Deletion cancelled.');

            return 0;
        }

        $deleted = false;

        // Delete repositories
        $repoPath = app_path("Repositories/{$name}");
        if (File::exists($repoPath)) {
            File::deleteDirectory($repoPath);
            $this->info("✓ Deleted: Repositories/{$name}");
            $deleted = true;
        }

        // Delete service
        $servicePath = app_path("Services/{$name}Service.php");
        if (File::exists($servicePath)) {
            File::delete($servicePath);
            $this->info("✓ Deleted: Services/{$name}Service.php");
            $deleted = true;
        }

        // Delete controller
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
            $this->info("✓ Deleted: Http/Controllers/{$name}Controller.php");
            $deleted = true;
        }

        // Delete form requests
        $requestPath = app_path("Http/Requests/{$name}");
        if (File::exists($requestPath)) {
            File::deleteDirectory($requestPath);
            $this->info("✓ Deleted: Http/Requests/{$name}");
            $deleted = true;
        }

        // Delete views
        $viewPath = resource_path("views/app/{$kebabName}");
        if (File::exists($viewPath)) {
            File::deleteDirectory($viewPath);
            $this->info("✓ Deleted: views/app/{$kebabName}");
            $deleted = true;
        }

        // Delete migrations if option provided
        if ($this->option('migrations')) {
            $tableName = Str::snake(Str::plural($name));
            $migrationPath = database_path('migrations');
            $migrations = File::files($migrationPath);

            foreach ($migrations as $migration) {
                if (strpos($migration->getFilename(), $tableName) !== false) {
                    File::delete($migration->getPathname());
                    $this->info("✓ Deleted: migrations/{$migration->getFilename()}");
                    $deleted = true;
                }
            }
        }

        // Remove from routes
        $this->removeFromRoutes($name);

        // Remove from service provider
        $this->removeFromServiceProvider($name);

        // Remove sidebar item
        $this->removeFromSidebar($kebabName);

        if ($deleted) {
            $this->info('All files have been deleted successfully.');
        } else {
            $this->warn("No files found to delete for '{$name}'.");
        }

        return 0;
    }

    private function removeFromRoutes(string $name): void
    {
        $routesPath = base_path('routes/web.php');

        if (! File::exists($routesPath)) {
            return;
        }

        $content = File::get($routesPath);
        $routeName = Str::kebab($name);

        // Remove routes related to this resource
        $pattern = "/Route::resource\(['\"]".preg_quote($routeName)."['\"][^)]*\);?\n?/";
        $updated = preg_replace($pattern, '', $content);

        if ($updated !== $content) {
            File::put($routesPath, $updated);
            $this->info("✓ Removed routes for '{$routeName}'");
        }
    }

    private function removeFromServiceProvider(string $name): void
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (! File::exists($providerPath)) {
            return;
        }

        $content = File::get($providerPath);

        // Remove binding
        $pattern = "/\\\$this->app->bind\(['\"]".preg_quote($name)."RepositoryInterface['\"][^)]*\);?\n?/";
        $updated = preg_replace($pattern, '', $content);

        if ($updated !== $content) {
            File::put($providerPath, $updated);
            $this->info("✓ Removed service provider binding for '{$name}'");
        }
    }

    private function removeFromSidebar(string $routeName): void
    {
        $sidebarPath = resource_path('views/components/sidebar.blade.php');

        if (! File::exists($sidebarPath)) {
            return;
        }

        $content = File::get($sidebarPath);

        // Remove sidebar item
        $pattern = '/<!--\\s*'.preg_quote($routeName)."\\s*-->[^<]*<li>[^<]*<a[^>]*route\\(['\"]".preg_quote($routeName)."['\"][^)]*\\)[^<]*<\\/a>[^<]*<\\/li>\\s*/i";
        $updated = preg_replace($pattern, '', $content);

        if ($updated !== $content) {
            File::put($sidebarPath, $updated);
            $this->info("✓ Removed sidebar item for '{$routeName}'");
        }
    }
}
