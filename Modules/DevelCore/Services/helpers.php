<?php

use Modules\DevelCore\Entities\Settings;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        return Settings::read($key, $default);
    }
}