<?php

namespace Devel\Modules\Generators;

use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command as Console;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Devel\Modules\FileRepository;
use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;

class ModuleGenerator extends Generator
{
    /**
     * The module name will created.
     *
     * @var string
     */
    protected $name;

    /**
     * The laravel config instance.
     *
     * @var Config
     */
    protected $config;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * The laravel console instance.
     *
     * @var Console
     */
    protected $console;

    /**
     * The module instance.
     *
     * @var \Devel\Modules\Module
     */
    protected $module;

    /**
     * Force status.
     *
     * @var bool
     */
    protected $force = false;

    /**
     * Generate a plain module.
     *
     * @var bool
     */
    protected $plain = false;

    /**
     * An alternative display name for the module
     *
     * @var string
     */
    protected $displayName;

    /**
     * A model to generate CRUD for
     *
     * @var string
     */
    protected $model = '';

    /**
     * The constructor.
     * @param $name
     * @param FileRepository $module
     * @param Config     $config
     * @param Filesystem $filesystem
     * @param Console    $console
     */
    public function __construct(
        $name,
        FileRepository $module = null,
        Config $config = null,
        Filesystem $filesystem = null,
        Console $console = null
    ) {
        $this->name = $name;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->console = $console;
        $this->module = $module;
    }

    /**
     * Set plain flag.
     *
     * @param bool $plain
     *
     * @return $this
     */
    public function setPlain($plain)
    {
        $this->plain = $plain;

        return $this;
    }

    /**
     * Get the name of module will created. By default in studly case.
     *
     * @return string
     */
    public function getName()
    {
        return Str::studly($this->name);
    }

    /**
     * Get the alternative display name of module.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName ?? $this->name;
    }

    /**
     * Get the controller name.
     *
     * @return string
     */
    public function getControllerName()
    {
        if ($this->model) {
            $parts = explode('\\', $this->model);

            return Str::plural($parts[count($parts) - 1]);
        } else {
            return $this->getName() . 'Controller';
        }
    }

    /**
     * Get the CRUD model class name.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model ?? '';
    }

    /**
     * Get the laravel config instance.
     *
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the laravel config instance.
     *
     * @param Config $config
     *
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get the laravel filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set the laravel filesystem instance.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get the laravel console instance.
     *
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Set the laravel console instance.
     *
     * @param Console $console
     *
     * @return $this
     */
    public function setConsole($console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get the module instance.
     *
     * @return \Devel\Modules\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set the module instance.
     *
     * @param mixed $module
     *
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get the list of folders will created.
     *
     * @return array
     */
    public function getFolders()
    {
        return $this->module->config('paths.generator');
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->module->config('stubs.files');
    }

    /**
     * Set force status.
     *
     * @param bool|int $force
     *
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Set display name status.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setDisplayName($name)
    {
        $this->displayName = $name;

        return $this;
    }

    /**
     * Set the CRUD model class name.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setModel($class)
    {
        $this->model = $class;

        return $this;
    }

    /**
     * Generate the module.
     */
    public function generate()
    {
        $name = $this->getName();

        if ($this->module->has($name)) {
            if ($this->force) {
                $this->module->delete($name);
            } else {
                $this->console->error("Module [{$name}] already exist!");

                return;
            }
        }

        $this->generateFolders();

        $this->generateModuleJsonFile();

        if ($this->plain !== true) {
            $this->generateFiles();
            $this->generateResources();
        }

        if ($this->plain === true) {
            $this->cleanModuleJsonFile();
        }

        $this->console->info("Module [{$name}] created successfully.");
    }

    /**
     * Generate the folders.
     */
    public function generateFolders()
    {
        foreach ($this->getFolders() as $key => $folder) {
            $folder = GenerateConfigReader::read($key);

            if ($folder->generate() === false) {
                continue;
            }

            $path = $this->module->getModulePath($this->getName()) . '/' . $folder->getPath();

            $this->filesystem->makeDirectory($path, 0755, true);
            if (config('devel-modules.stubs.gitkeep')) {
                $this->generateGitKeep($path);
            }
        }
    }

    /**
     * Generate git keep to the specified path.
     *
     * @param string $path
     */
    public function generateGitKeep($path)
    {
        $this->filesystem->put($path . '/.gitkeep', '');
    }

    /**
     * Generate the files.
     */
    public function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->module->getModulePath($this->getName()) . $file;

            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents($stub));

            $this->console->info("Created : {$path}");
        }
    }

    /**
     * Generate some resources.
     */
    public function generateResources()
    {
        if (GenerateConfigReader::read('seeder')->generate() === true) {
            $this->console->call('module:make-seed', [
                'name' => $this->getName(),
                'module' => $this->getName(),
                '--master' => true,
            ]);
        }

        if (GenerateConfigReader::read('provider')->generate() === true) {
            $this->console->call('module:make-provider', [
                'name' => $this->getName() . 'ServiceProvider',
                'module' => $this->getName(),
                '--master' => true,
            ]);
            $this->console->call('module:route-provider', [
                'module' => $this->getName(),
            ]);
        }

        // Generate the SettingsController
        $this->console->call('module:make-controller', [
            'controller' => $this->getControllerName(),
            'module' => $this->getName(),
            '--settings' => true,
        ]);

        // Generate the SettingsSeeder
        $this->console->call('module:make-seed', [
            'name' => 'SettingsSeeder',
            'module' => $this->getName(),
            '--settings' => true,
        ]);
        
        if ($this->getModel()) {
            // Generate CRUD for the provided model
            $this->console->call('module:make-crud', [
                'module' => $this->getName(),
                'model' => $this->getModel(),
                '--name' => $this->getDisplayName(),
            ]);
        }
    }

    /**
     * Get the contents of the specified stub file by given stub name.
     *
     * @param $stub
     *
     * @return string
     */
    public function getStubContents($stub)
    {
        return (new Stub(
            '/' . $stub . '.stub',
            $this->getReplacement($stub)
        )
        )->render();
    }

    /**
     * get the list for the replacements.
     */
    public function getReplacements()
    {
        return $this->module->config('stubs.replacements');
    }

    /**
     * Get array replacement for the specified stub.
     *
     * @param $stub
     *
     * @return array
     */
    protected function getReplacement($stub)
    {
        $replacements = $this->module->config('stubs.replacements');

        if (!isset($replacements[$stub])) {
            return [];
        }

        $keys = $replacements[$stub];

        $replaces = [];

        if ($stub === 'json' || $stub === 'composer') {
            if (in_array('PROVIDER_NAMESPACE', $keys, true) === false) {
                $keys[] = 'PROVIDER_NAMESPACE';
            }
        }
        foreach ($keys as $key) {
            if (method_exists($this, $method = 'get' . ucfirst(Str::studly(strtolower($key))) . 'Replacement')) {
                $replaces[$key] = $this->$method();
            } else {
                $replaces[$key] = null;
            }
        }

        return $replaces;
    }

    /**
     * Generate the module.json file
     */
    private function generateModuleJsonFile()
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        if (!$this->filesystem->isDirectory($dir = dirname($path))) {
            $this->filesystem->makeDirectory($dir, 0775, true);
        }

        $this->filesystem->put($path, $this->getStubContents('json'));

        $this->console->info("Created : {$path}");
    }

    /**
     * Remove the default service provider that was added in the module.json file
     * This is needed when a --plain module was created
     */
    private function cleanModuleJsonFile()
    {
        $path = $this->module->getModulePath($this->getName()) . 'module.json';

        $content = $this->filesystem->get($path);
        $namespace = $this->getModuleNamespaceReplacement();
        $studlyName = $this->getStudlyNameReplacement();

        $provider = '"' . $namespace . '\\\\' . $studlyName . '\\\\Providers\\\\' . $studlyName . 'ServiceProvider"';

        $content = str_replace($provider, '', $content);

        $this->filesystem->put($path, $content);
    }

    /**
     * Get the module name in lower case.
     *
     * @return string
     */
    protected function getLowerNameReplacement()
    {
        return strtolower($this->getName());
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return $this->getName();
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getDisplayNameReplacement()
    {
        return $this->getDisplayName();
    }

    /**
     * Get the module first controller name.
     *
     * @return string
     */
    protected function getControllerNameReplacement()
    {
        if ($this->model) {
            $parts = explode('\\', $this->model);

            return Str::plural($parts[count($parts) - 1]) . 'Controller';
        } else {
            return $this->getName() . 'Controller';
        }
    }

    /**
     * Get the CRUD model class name.
     *
     * @return string
     */
    protected function getModelReplacement()
    {
        return $this->getModel();
    }

    /**
     * Get the CRUD model class name.
     *
     * @return string
     */
    protected function getPermissionGroupReplacement()
    {
        $moduleParts = preg_split(
            '/(?=[A-Z])/', $this->getStudlyNameReplacement(), -1, PREG_SPLIT_NO_EMPTY
        );

        return strtolower(implode('_', $moduleParts));
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getSlugReplacement()
    {
        return Str::slug($this->getDisplayName());
    }

    /**
     * Get replacement for $VENDOR$.
     *
     * @return string
     */
    protected function getVendorReplacement()
    {
        return $this->module->config('composer.vendor');
    }

    /**
     * Get replacement for $MODULE_NAMESPACE$.
     *
     * @return string
     */
    protected function getModuleNamespaceReplacement()
    {
        return str_replace('\\', '\\\\', $this->module->config('namespace'));
    }

    /**
     * Get replacement for $AUTHOR_NAME$.
     *
     * @return string
     */
    protected function getAuthorNameReplacement()
    {
        return $this->module->config('composer.author.name');
    }

    /**
     * Get replacement for $AUTHOR_EMAIL$.
     *
     * @return string
     */
    protected function getAuthorEmailReplacement()
    {
        return $this->module->config('composer.author.email');
    }

    /**
     * Get replacement for $PACKAGE_NAME$.
     *
     * @return string
     */
    protected function getPackageNameReplacement()
    {
        return str_replace('_', '-', Str::snake($this->name)) . '-devel-module';
    }

    protected function getProviderNamespaceReplacement(): string
    {
        return str_replace('\\', '\\\\', GenerateConfigReader::read('provider')->getNamespace());
    }
}
