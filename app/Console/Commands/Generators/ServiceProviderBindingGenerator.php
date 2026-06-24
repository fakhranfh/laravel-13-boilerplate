<?php

namespace App\Console\Commands\Generators;

use Illuminate\Filesystem\Filesystem;

class ServiceProviderBindingGenerator
{
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function generate(string $name, callable $callback): void
    {
        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');
        if (!$this->filesystem->exists($appServiceProviderPath)) {
            return;
        }

        $content = $this->filesystem->get($appServiceProviderPath);

        $this->addImports($name, $appServiceProviderPath, $content, $callback);
        $this->addBinding($name, $appServiceProviderPath, $content, $callback);
    }

    private function addImports(string $name, string $appServiceProviderPath, string &$content, callable $callback): void
    {
        $interfaceImport = "use App\\Repositories\\{$name}\\{$name}RepositoryInterface;";
        $repositoryImport = "use App\\Repositories\\{$name}\\{$name}Repository;";

        // Check if already imported
        if (strpos($content, $interfaceImport) !== false || strpos($content, $repositoryImport) !== false) {
            return;
        }

        $lines = explode("\n", $content);
        $lastUseIndex = -1;
        foreach ($lines as $i => $line) {
            if (preg_match('/^use\s+[\w\\\\]+;/', $line)) {
                $lastUseIndex = $i;
            }
        }

        if ($lastUseIndex !== -1) {
            array_splice($lines, $lastUseIndex + 1, 0, [$interfaceImport, $repositoryImport]);
            $content = implode("\n", $lines);
            $this->filesystem->put($appServiceProviderPath, $content);
            $callback("Imports for {$name}RepositoryInterface and {$name}Repository added to AppServiceProvider.", 'info');
        }
    }

    private function addBinding(string $name, string $appServiceProviderPath, string $content, callable $callback): void
    {
        $interface = "{$name}RepositoryInterface";
        $repository = "{$name}Repository";
        $bindLine = "\$this->app->bind({$interface}::class, {$repository}::class);";

        if (strpos($content, $bindLine) !== false) {
            $callback("Binding for {$interface} already exists in AppServiceProvider.", 'warn');
            return;
        }

        $pattern = '/public function register\(\): void\s*\{([\s\S]*?)\}/m';
        if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            $registerBody = $matches[1][0];
            $insertPos = $matches[1][1] + strlen($registerBody);
            $newContent = substr($content, 0, $insertPos) . "  {$bindLine}\n" . substr($content, $insertPos);
            $this->filesystem->put($appServiceProviderPath, $newContent);
            $callback("Binding for {$interface} added to AppServiceProvider.", 'info');
        } else {
            $callback("Could not find register() method in AppServiceProvider. Please add the following manually:\n{$bindLine}", 'warn');
        }
    }
}
