<?php

namespace Devel\Core\Services;

class MetaTags
{
    /**
     * Meta tags collection
     *
     * @var array
     */
    protected static $tags = [];

    /**
     * HTML templates of tags
     * 
     * @var array
     */
    const TEMPLATES = [
        'title' => '<title>{value}</title>',
        'description' => '<meta name="description" content="{value}">',
        'keywords' => '<meta name="keywords" content="{value}">',
    ];

    /**
     * Get the meta tags collection
     *
     * @return array
     */
    public static function getTags(): array
    {
        return static::$tags;
    }

    /**
     * Set a meta tag
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public static function setTag(string $name, string $value): void
    {
        if (!isset(static::$tags[$name])) {
            static::$tags[$name] = new MetaTag($name, $value);
        } else {
            static::$tags[$name]->addValue($value);
        }
    }

    /**
     * Set one or more meta tags
     *
     * @param array $tags
     * @return void
     */
    public static function setTags(array $tags): void
    {
        foreach ($tags as $name => $value) {
            static::setTag($name, $value);
        }
    }
}
