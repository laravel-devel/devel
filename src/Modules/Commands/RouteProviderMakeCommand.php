<?php

namespace Devel\Modules\Commands;

use Devel\Modules\Support\Config\GenerateConfigReader;
use Devel\Modules\Support\Stub;
use Devel\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteProviderMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'module';

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'module:route-provider';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create a new route service provider for the specified module.';

    /**
     * The command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module to generate the route service provider for.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the file already exists.'],
        ];
    }

    /**
     * Get template contents.
     *
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['devel-modules']->findOrFail($this->getModuleName());

        return (new Stub('/route-provider.stub', [
            'NAMESPACE'            => $this->getClassNamespace($module),
            'CLASS'                => $this->getFileName(),
            'MODULE_NAMESPACE'     => $this->laravel['devel-modules']->config('namespace'),
            'MODULE'               => $this->getModuleName(),
            'CONTROLLER_NAMESPACE' => $this->getControllerNameSpace(),
            'PUBLIC_ROUTES_PATH'      => $this->getPublicRoutesPath(),
            'DASHBOARD_ROUTES_PATH' => $this->getDashboardRoutesPath(),
            'API_ROUTES_PATH'      => $this->getApiRoutesPath(),
            'LOWER_NAME'           => $module->getLowerName(),
        ]))->render();
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return 'RouteServiceProvider';
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['devel-modules']->getModulePath($this->getModuleName());

        $generatorPath = GenerateConfigReader::read('provider');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return mixed
     */
    protected function getPublicRoutesPath()
    {
        return '/' . $this->laravel['devel-modules']->config('stubs.files.routes/public', 'Routes/public.php');
    }

    /**
     * @return mixed
     */
    protected function getDashboardRoutesPath()
    {
        return '/' . $this->laravel['devel-modules']->config('stubs.files-crud.routes/dashboard', 'Routes/dashboard.php');
    }

    /**
     * @return mixed
     */
    protected function getApiRoutesPath()
    {
        return '/' . $this->laravel['devel-modules']->config('stubs.files.routes/api', 'Routes/api.php');
    }

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['devel-modules'];

        return $module->config('paths.generator.provider.namespace') ?: $module->config('paths.generator.provider.path', 'Providers');
    }

    /**
     * @return string
     */
    private function getControllerNameSpace(): string
    {
        $module = $this->laravel['devel-modules'];

        return str_replace('/', '\\', $module->config('paths.generator.controller.namespace') ?: $module->config('paths.generator.controller.path', 'Controller'));
    }
}
