<?php

namespace App\Console\Commands\Stubs;

class RepositoryStubGenerator
{
    public function generate(string $name): string
    {
        return <<<PHP
<?php

namespace App\Repositories\\{$name};

use App\Models\\{$name};

class {$name}Repository implements {$name}RepositoryInterface
{
    public function query(array \$filters = [])
    {
        \$query = {$name}::query();

        foreach (\$filters as \$key => \$value) {
            if (is_null(\$value) || \$value === '') {
                continue;
            }

            \$query->where(\$key, \$value);
        }

        return \$query;
    }

    public function get(array \$filters = [], array \$with = [])
    {
        \$query = \$this->query(\$filters);

        return \$query->with(\$with)->get();
    }

    public function getAll()
    {
        return {$name}::all();
    }

    public function find(\$id)
    {
        return {$name}::find(\$id);
    }

    public function create(array \$data)
    {
        return {$name}::create(\$data);
    }

    public function update(\$id, array \$data)
    {
        \$model = {$name}::findOrFail(\$id);
        \$model->update(\$data);
        return \$model;
    }

    public function delete(\$id)
    {
        return {$name}::destroy(\$id);
    }
}
PHP;
    }
}
