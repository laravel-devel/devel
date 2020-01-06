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

    public static function addGroup(string $category, string $name)
    {
        if (!isset(static::$items[$category])) {
            static::addCategory($category);
        }

        if (!isset(static::$items[$category][$name])) {
            static::$items[$category][$name] = [];
        }
    }

    public static function addItems(string $category, string $group, array $items)
    {
        if (!isset(static::$items[$category])) {
            static::addCategory($category);
        }

        if (!isset(static::$items[$category][$group])) {
            static::addGroup($category, $group);
        }

        static::$items[$category][$group] = array_merge(
            static::$items[$category][$group], $items
        );
    }
}
