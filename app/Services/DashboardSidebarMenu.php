<?php

namespace App\Services;

class DashboardSidebarMenu
{
    protected static $items = [];

    public static function getItems()
    {
        return static::$items;
    }

    public static function addCategory(string $name)
    {
        if (!isset(static::$items[$name])) {
            static::$items[$name] = [];
        }
    }

    public static function addItem(string $category, array $path, string $uri = null)
    {
        if (!isset(static::$items[$category])) {
            static::addCategory($category);
        }

        $pos = &static::$items[$category];
        $i = 1;

        foreach ($path as $part) {
            if (!isset($pos[$part])) {
                $pos[$part] = [
                    'url' => null,
                    'children' => [],
                ];
            }

            if ($i === count($path)) {
                $pos = &$pos[$part];
                $pos['url'] = url($uri);

                break;
            }

            $pos = &$pos[$part]['children'];
            $i++;
        }
    }
}
