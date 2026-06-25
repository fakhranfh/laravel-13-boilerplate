<?php

namespace App\Console\Commands\Generators;

use App\Console\Commands\Stubs\TailwindBladeCreateStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeEditStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeIndexStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeShowStubGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class BladeGenerator
{
    private Filesystem $filesystem;

    private TailwindBladeIndexStubGenerator $indexStubGenerator;

    private TailwindBladeCreateStubGenerator $createStubGenerator;

    private TailwindBladeEditStubGenerator $editStubGenerator;

    private TailwindBladeShowStubGenerator $showStubGenerator;

    public function __construct()
    {
        $this->filesystem = new Filesystem;
        $this->indexStubGenerator = new TailwindBladeIndexStubGenerator;
        $this->createStubGenerator = new TailwindBladeCreateStubGenerator;
        $this->editStubGenerator = new TailwindBladeEditStubGenerator;
        $this->showStubGenerator = new TailwindBladeShowStubGenerator;
    }

    public function generate(string $name, string $label, string $viewPath, callable $callback): void
    {
        $kebabCaseName = Str::kebab($name);
        $bladeDir = resource_path("views/{$viewPath}/{$kebabCaseName}");

        $indexBladePath = "{$bladeDir}/index.blade.php";
        $createBladePath = "{$bladeDir}/create.blade.php";
        $editBladePath = "{$bladeDir}/edit.blade.php";
        $showBladePath = "{$bladeDir}/show.blade.php";

        $this->filesystem->ensureDirectoryExists($bladeDir);

        // Generate Index Blade
        $indexContent = $this->indexStubGenerator->generate($name, $label);
        $indexContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $indexContent);

        if (! $this->filesystem->exists($indexBladePath)) {
            $this->filesystem->put($indexBladePath, $indexContent);
            $callback("Blade index view created: {$indexBladePath}", 'info');
        } else {
            $callback("Blade index view already exists: {$indexBladePath}", 'warn');
        }

        // Generate Create Blade
        $createContent = $this->createStubGenerator->generate($name, $label);
        $createContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $createContent);

        if (! $this->filesystem->exists($createBladePath)) {
            $this->filesystem->put($createBladePath, $createContent);
            $callback("Blade create view created: {$createBladePath}", 'info');
        } else {
            $callback("Blade create view already exists: {$createBladePath}", 'warn');
        }

        // Generate Edit Blade
        $editContent = $this->editStubGenerator->generate($name, $label);
        $editContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $editContent);

        if (! $this->filesystem->exists($editBladePath)) {
            $this->filesystem->put($editBladePath, $editContent);
            $callback("Blade edit view created: {$editBladePath}", 'info');
        } else {
            $callback("Blade edit view already exists: {$editBladePath}", 'warn');
        }

        // Generate Show Blade
        $showContent = $this->showStubGenerator->generate($name, $label);
        $showContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $showContent);

        if (! $this->filesystem->exists($showBladePath)) {
            $this->filesystem->put($showBladePath, $showContent);
            $callback("Blade show view created: {$showBladePath}", 'info');
        } else {
            $callback("Blade show view already exists: {$showBladePath}", 'warn');
        }

        // Add sidebar button
        $this->addSidebarItem($kebabCaseName, $label, $callback);
    }

    private function addSidebarItem(string $routeName, string $label, callable $callback): void
    {
        $sidebarPath = resource_path('views/components/sidebar.blade.php');

        if (! $this->filesystem->exists($sidebarPath)) {
            $callback("Sidebar component not found: {$sidebarPath}", 'error');

            return;
        }

        $sidebarContent = $this->filesystem->get($sidebarPath);

        // Create the new sidebar item
        $icon = $this->getIconForRoute($routeName);
        $sidebarItem = <<<BLADE
            <!-- {$label} -->
            <li>
                <a href="{{ route('{$routeName}.index') }}" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg text-black hover:bg-primary/10 transition-colors duration-150 {{ request()->routeIs('{$routeName}.*') ? 'bg-primary/20 text-primary' : 'hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[24px]">{$icon}</span>
                    <span class="font-body-md text-body-md">{$label}</span>
                </a>
            </li>
            BLADE;

        // Check if the route is already in sidebar
        if (strpos($sidebarContent, "route('{$routeName}.index')") !== false) {
            $callback("Sidebar item for '{$label}' already exists", 'warn');

            return;
        }

        // Find the closing </ul> tag and insert before it
        $updatedContent = str_replace(
            '        </ul>',
            $sidebarItem."\n        </ul>",
            $sidebarContent
        );

        $this->filesystem->put($sidebarPath, $updatedContent);
        $callback("Sidebar item added for '{$label}'", 'info');
    }

    private function getIconForRoute(string $routeName): string
    {
        $iconMap = [
            'dashboard' => 'dashboard',
            'user' => 'person',
            'product' => 'shopping_cart',
            'order' => 'receipt',
            'category' => 'category',
            'setting' => 'settings',
            'report' => 'assessment',
            'profile' => 'account_circle',
        ];

        return $iconMap[$routeName] ?? 'folder';
    }
}
