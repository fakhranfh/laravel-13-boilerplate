<?php

namespace App\Console\Commands;

use App\Console\Commands\Generators\BladeGenerator;
use App\Console\Commands\Generators\ControllerGenerator;
use App\Console\Commands\Generators\FormRequestGenerator;
use App\Console\Commands\Generators\MigrationGenerator;
use App\Console\Commands\Generators\ModelGenerator;
use App\Console\Commands\Generators\RepositoryGenerator;
use App\Console\Commands\Generators\RouteGenerator;
use App\Console\Commands\Generators\ServiceGenerator;
use App\Console\Commands\Generators\ServiceProviderBindingGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MakeRepositoryServiceController extends Command
{
    protected $signature = 'make:rsc {name} {--label=} {--view-path=} {--repository-service-only}';

    protected $description = 'Generate Repository, Service, and Controller for a given name';

    private array $columnInputTypes = [];

    private array $generatedFiles = [];

    private array $columns = [];

    public function handle(): int
    {
        try {
            $name = $this->argument('name');
            $label = $this->option('label') ?: $name;
            $viewPath = $this->option('view-path') ?: 'app';

            // Record initial file state for rollback on error
            $fileSnapshot = $this->captureFileSnapshot($name, $viewPath);

            if ($this->confirm('Do you want to create a migration?', true)) {
                $this->generateMigration($name);
            }

            $this->generateModel($name, $this->columns);

            $this->generateRepository($name);
            $this->generateService($name, $label);
            $this->bindToServiceProvider($name);

            if ($this->option('repository-service-only')) {
                return 0;
            }

            $this->generateController($name, $label, $viewPath);
            $this->generateFormRequests($name);

            // Configure form input types if not already done via migration
            if (empty($this->columnInputTypes)) {
                $this->configureFormInputTypes($name);
            }

            $this->generateBladeViews($name, $label, $viewPath);
            $this->generateRoutes($name, $label);

            Artisan::call('optimize');
            $this->info('php artisan optimize executed.');

            return 0;
        } catch (\Exception $e) {
            $this->error("Error occurred: {$e->getMessage()}");
            $this->warn('Cleaning up generated files...');
            $this->cleanupGeneratedFiles($name ?? null, $viewPath ?? 'app');
            $this->error('Generation failed and files have been cleaned up.');

            return 1;
        }
    }

    private function generateModel(string $name, array $columns = []): void
    {
        $generator = new ModelGenerator;
        $generator->generate($name, $columns, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateRepository(string $name): void
    {
        $generator = new RepositoryGenerator;
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateService(string $name, string $label): void
    {
        $generator = new ServiceGenerator;
        $generator->generate($name, $label, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateController(string $name, string $label, string $viewPath = 'app'): void
    {
        $generator = new ControllerGenerator;
        $generator->generate($name, $label, $viewPath, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateFormRequests(string $name): void
    {
        $generator = new FormRequestGenerator;
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateRoutes(string $name, string $label): void
    {
        $generator = new RouteGenerator;
        $generator->generate($name, $label, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function bindToServiceProvider(string $name): void
    {
        $generator = new ServiceProviderBindingGenerator;
        $generator->generate($name, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateBladeViews(string $name, string $label, string $viewPath): void
    {
        $generator = new BladeGenerator;
        $generator->generate($name, $label, $viewPath, $this->columnInputTypes, function (string $message, string $type) {
            $this->$type($message);
        });
    }

    private function generateMigration(string $name): void
    {
        $columns = [];

        $this->info('Define your table columns (type "done" when finished):');

        while (true) {
            $this->newLine();
            $columnName = $this->ask('Column name (or "done" to finish)');

            if (strtolower($columnName) === 'done') {
                break;
            }

            if (empty($columnName)) {
                $this->warn('Column name cannot be empty.');

                continue;
            }

            $columnType = $this->choice(
                'Column type',
                [
                    'string',
                    'integer',
                    'bigInteger',
                    'smallInteger',
                    'decimal',
                    'float',
                    'boolean',
                    'text',
                    'longText',
                    'date',
                    'dateTime',
                    'timestamp',
                    'json',
                    'enum',
                ]
            );

            $column = [
                'name' => $columnName,
                'type' => $columnType,
            ];

            // Type-specific options
            if ($columnType === 'string') {
                $length = $this->ask('String length (press Enter for default 255)', 255);
                $column['length'] = (int) $length;
            } elseif ($columnType === 'decimal') {
                $precision = $this->ask('Precision (total digits)', 8);
                $scale = $this->ask('Scale (decimal places)', 2);
                $column['precision'] = (int) $precision;
                $column['scale'] = (int) $scale;
            } elseif ($columnType === 'enum') {
                $values = $this->ask('Enum values (comma-separated)');
                $column['values'] = array_map('trim', explode(',', $values));
            }

            // Common options
            $column['nullable'] = $this->confirm('Nullable?', false);

            if ($this->confirm('Add default value?', false)) {
                $default = $this->ask('Default value');
                if ($columnType === 'boolean') {
                    $column['default'] = strtolower($default) === 'true' || $default === '1';
                } else {
                    $column['default'] = $default;
                }
            }

            if ($columnType !== 'json' && $columnType !== 'text' && $columnType !== 'longText') {
                $column['index'] = $this->confirm('Add index?', false);
            }

            if ($columnType === 'string') {
                $column['unique'] = $this->confirm('Add unique constraint?', false);
            }

            // Ask for form input type
            $column['input_type'] = $this->askForInputType($columnType, $columnName);

            $columns[] = $column;
            $this->info("<fg=green>✓ Column '{$columnName}' added</>");
        }

        if (empty($columns)) {
            $this->warn('No columns defined. Skipping migration creation.');

            return;
        }

        // Store columns and input types
        $this->columns = $columns;
        $this->columnInputTypes = array_reduce($columns, function ($carry, $column) {
            $carry[$column['name']] = $column['input_type'];

            return $carry;
        }, []);

        $generator = new MigrationGenerator;
        $generator->generate($name, $columns, function (string $message, string $type) {
            $this->$type($message);
        });

        if ($this->confirm('Run migration now?', true)) {
            $tableName = Str::snake(Str::plural($name));

            // Check if table already exists and drop it
            if (Schema::hasTable($tableName)) {
                $this->warn("Table '{$tableName}' already exists.");
                if ($this->confirm('Drop and recreate the table?', true)) {
                    Schema::drop($tableName);
                    $this->info("Table '{$tableName}' dropped.");
                } else {
                    $this->info('Skipping migration.');

                    return;
                }
            }

            Artisan::call('migrate');
            $this->info('Migration executed successfully.');
        } else {
            $this->info('Run <comment>php artisan migrate</comment> to execute the migration later.');
        }
    }

    private function askForInputType(string $columnType, string $columnName): string
    {
        $inputTypeOptions = $this->getInputTypeOptionsForColumnType($columnType);

        return $this->choice(
            "Input type for '{$columnName}' field",
            $inputTypeOptions
        );
    }

    private function getInputTypeOptionsForColumnType(string $columnType): array
    {
        return match ($columnType) {
            'boolean' => ['radio', 'checkbox'],
            'text', 'longText' => ['textarea'],
            'date' => ['date'],
            'dateTime', 'timestamp' => ['datetime-local'],
            'integer', 'smallInteger', 'bigInteger' => ['number'],
            'decimal', 'float' => ['number'],
            'json' => ['textarea'],
            'enum' => ['select'],
            'string' => ['text', 'email', 'password', 'url', 'tel'],
            default => ['text'],
        };
    }

    private function configureFormInputTypes(string $name): void
    {
        $tableName = Str::snake(Str::plural($name));

        // Check if table exists in database
        if (! Schema::hasTable($tableName)) {
            $this->warn("Table '{$tableName}' not found in database. Skipping input type configuration.");

            return;
        }

        $this->info('Configure form input types for columns:');
        $columns = Schema::getColumnListing($tableName);
        $exclude = ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token', 'password'];

        // Get column types
        $columnTypes = [];
        foreach ($columns as $col) {
            if (! in_array($col, $exclude)) {
                $column = Schema::getColumn($tableName, $col);
                $columnTypes[$col] = $column['type'] ?? 'string';
            }
        }

        // Ask for input type for each column
        foreach ($columnTypes as $col => $type) {
            $colLabel = Str::title(str_replace('_', ' ', $col));
            $inputType = $this->choice(
                "Input type for '{$colLabel}' field",
                $this->getInputTypeOptionsForColumnType($type)
            );
            $this->columnInputTypes[$col] = $inputType;
        }
    }

    private function captureFileSnapshot(string $name, string $viewPath): array
    {
        $snapshot = [];
        $kebabName = Str::kebab($name);

        // Models
        $modelPath = app_path("Models/{$name}.php");
        if (File::exists($modelPath)) {
            $snapshot['models'] = [$modelPath];
        }

        // Repositories
        $repoPath = app_path("Repositories/{$name}");
        if (File::exists($repoPath)) {
            $snapshot['repositories'] = File::files($repoPath);
        }

        // Services
        $servicePath = app_path('Services');
        if (File::exists($servicePath)) {
            $snapshot['services'] = File::files($servicePath);
        }

        // Controllers
        $controllerPath = app_path('Http/Controllers');
        if (File::exists($controllerPath)) {
            $snapshot['controllers'] = File::files($controllerPath);
        }

        // Requests
        $requestPath = app_path("Http/Requests/{$name}");
        if (File::exists($requestPath)) {
            $snapshot['requests'] = File::files($requestPath);
        }

        // Views
        $viewsPath = resource_path("views/{$viewPath}/{$kebabName}");
        if (File::exists($viewsPath)) {
            $snapshot['views'] = File::files($viewsPath);
        }

        return $snapshot;
    }

    private function cleanupGeneratedFiles(?string $name, string $viewPath): void
    {
        if (! $name) {
            return;
        }

        $kebabName = Str::kebab($name);

        // Delete model
        $modelPath = app_path("Models/{$name}.php");
        if (File::exists($modelPath)) {
            File::delete($modelPath);
            $this->info("Deleted: {$modelPath}");
        }

        // Delete repositories
        $repoPath = app_path("Repositories/{$name}");
        if (File::exists($repoPath)) {
            File::deleteDirectory($repoPath);
            $this->info("Deleted: {$repoPath}");
        }

        // Delete service
        $servicePath = app_path("Services/{$name}Service.php");
        if (File::exists($servicePath)) {
            File::delete($servicePath);
            $this->info("Deleted: {$servicePath}");
        }

        // Delete controller
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
            $this->info("Deleted: {$controllerPath}");
        }

        // Delete form requests
        $requestPath = app_path("Http/Requests/{$name}");
        if (File::exists($requestPath)) {
            File::deleteDirectory($requestPath);
            $this->info("Deleted: {$requestPath}");
        }

        // Delete views
        $viewsPath = resource_path("views/{$viewPath}/{$kebabName}");
        if (File::exists($viewsPath)) {
            File::deleteDirectory($viewsPath);
            $this->info("Deleted: {$viewsPath}");
        }
    }
}
