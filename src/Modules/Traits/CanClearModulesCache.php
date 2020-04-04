<?php

namespace Devel\Modules\Traits;

trait CanClearModulesCache
{
    /**
     * Clear the modules cache if it is enabled
     */
    public function clearCache()
    {
        if (config('devel-modules.cache.enabled') === true) {
            app('cache')->forget(config('devel-modules.cache.key'));
        }
    }
}
