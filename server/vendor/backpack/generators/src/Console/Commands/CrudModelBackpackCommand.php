<?php

namespace Backpack\Generators\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudModelBackpackCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'backpack:crud-model';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backpack:crud-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a Backpack CRUD model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * The trait that allows a model to have an admin panel.
     *
     * @var string
     */
    protected $crudTrait = 'Backpack\CRUD\app\Models\Traits\CrudTrait';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. We just make sure it uses CrudTrait
        // We add that one line. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
             ! $this->option('force')) &&
             $this->alreadyExists($this->getNameInput())) {
            $file = $this->files->get($path);
            $file_array = preg_split('/(\r\n)|\r|\n/', $file);

            // check if it already uses CrudTrait
            // if it does, do nothing
            if (Str::contains($file, [$this->crudTrait])) {
                $this->info('Model already exists and uses CrudTrait.');

                return false;
            }

            // if it does not have CrudTrait, add the trait on the Model

            $classDefinition = 'class '.$this->getNameInput().' extends';

            foreach ($file_array as $key => $line) {
                if (Str::contains($line, $classDefinition)) {
                    if (Str::endsWith($line, '{')) {
                        // add the trait on the next
                        $position = $key + 1;
                    } elseif ($file_array[$key + 1] == '{') {
                        // add the trait on the next next line
                        $position = $key + 2;
                    }

                    // keep in mind that the line number shown in IDEs is not
                    // the same as the array index - arrays start counting from 0,
                    // IDEs start counting from 1

                    // add CrudTrait
                    array_splice($file_array, $position, 0, '    use \\'.$this->crudTrait.';');

                    // save the file
                    $this->files->put($path, implode(PHP_EOL, $file_array));

                    // let the user know what we've done
                    $this->info('Model already exists! We just added CrudTrait on it.');

                    return false;
                }
            }

            $this->error('Model already exists! Could not add CrudTrait - please add manually.');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildClass($name)));

        $this->info($this->type.' created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../stubs/crud-model.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }

    /**
     * Replace the table name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceTable(&$stub, $name)
    {
        $name = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', str_replace($this->getNamespace($name).'\\', '', $name))), '_');

        $table = Str::snake(Str::plural($name));

        $stub = str_replace('DummyTable', $table, $stub);

        return $this;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceTable($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [

        ];
    }
}
