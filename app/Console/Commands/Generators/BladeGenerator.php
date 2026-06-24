<?php

namespace App\Console\Commands\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use App\Console\Commands\Stubs\TailwindBladeIndexStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeCreateStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeEditStubGenerator;
use App\Console\Commands\Stubs\TailwindBladeShowStubGenerator;

class BladeGenerator
{
    private Filesystem $filesystem;
    private TailwindBladeIndexStubGenerator $indexStubGenerator;
    private TailwindBladeCreateStubGenerator $createStubGenerator;
    private TailwindBladeEditStubGenerator $editStubGenerator;
    private TailwindBladeShowStubGenerator $showStubGenerator;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->indexStubGenerator = new TailwindBladeIndexStubGenerator();
        $this->createStubGenerator = new TailwindBladeCreateStubGenerator();
        $this->editStubGenerator = new TailwindBladeEditStubGenerator();
        $this->showStubGenerator = new TailwindBladeShowStubGenerator();
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

        if (!$this->filesystem->exists($indexBladePath)) {
            $this->filesystem->put($indexBladePath, $indexContent);
            $callback("Blade index view created: {$indexBladePath}", 'info');
        } else {
            $callback("Blade index view already exists: {$indexBladePath}", 'warn');
        }

        // Generate Create Blade
        $createContent = $this->createStubGenerator->generate($name, $label);
        $createContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $createContent);

        if (!$this->filesystem->exists($createBladePath)) {
            $this->filesystem->put($createBladePath, $createContent);
            $callback("Blade create view created: {$createBladePath}", 'info');
        } else {
            $callback("Blade create view already exists: {$createBladePath}", 'warn');
        }

        // Generate Edit Blade
        $editContent = $this->editStubGenerator->generate($name, $label);
        $editContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $editContent);

        if (!$this->filesystem->exists($editBladePath)) {
            $this->filesystem->put($editBladePath, $editContent);
            $callback("Blade edit view created: {$editBladePath}", 'info');
        } else {
            $callback("Blade edit view already exists: {$editBladePath}", 'warn');
        }

        // Generate Show Blade
        $showContent = $this->showStubGenerator->generate($name, $label);
        $showContent = str_replace(['LABEL', 'ROUTENAME'], [$label, Str::kebab($name)], $showContent);

        if (!$this->filesystem->exists($showBladePath)) {
            $this->filesystem->put($showBladePath, $showContent);
            $callback("Blade show view created: {$showBladePath}", 'info');
        } else {
            $callback("Blade show view already exists: {$showBladePath}", 'warn');
        }
    }
}
