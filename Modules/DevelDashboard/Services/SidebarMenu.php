<?php

namespace Modules\DevelDashboard\Services;

class SidebarMenu
{
    /**
     * Menu items tree
     *
     * @var array
     */
    protected static $items = [];

    /**
     * Get the menu items tree
     *
     * @return array
     */
    public static function getItems(): array
    {
        return static::$items;
    }

    /**
     * Get the menu items tree sorted alphabetically
     *
     * @return array
     */
    public static function getSortedItems(): array
    {
        $sorted = static::$items;

        // Sort groups
        ksort($sorted);

        // Sort items within groups
        foreach ($sorted as $group => $items) {
            ksort($items);

            $sorted[$group] = $items;
        }

        return $sorted;
    }

    /**
     * Add a category to the menu
     *
     * @param string $name
     * @return void
     */
    public static function addCategory(string $name)
    {
        if (!isset(static::$items[$name])) {
            static::$items[$name] = [];
        }
    }

    /**
     * Add an item to the menu
     *
     * @param string $category
     * @param array $path
     * @param string $uri
     * @return void
     */
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
