<?php

namespace Devel\Modules\Support\Config;

class GenerateConfigReader
{
    public static function read(string $value) : GeneratorPath
    {
        return new GeneratorPath(config("devel-modules.paths.generator.$value"));
    }
}
