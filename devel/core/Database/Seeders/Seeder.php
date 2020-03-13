<?php

namespace Devel\Core\Database\Seeders;

use Illuminate\Database\Seeder as IlluminateSeeder;

abstract class Seeder extends IlluminateSeeder
{
    /**
     * Revert the changes made by the seeder.
     *
     * @return void
     */
    public function revert()
    {
        //
    }

    /**
     * Call the revert() method of a given class.
     *
     * @param  array|string  $class
     * @param  bool  $silent
     * @return $this
     */
    public function uncall($class, $silent = false)
    {
        $seeder = $this->resolve($class);

        $seeder->revert();

        return $this;
    }
}