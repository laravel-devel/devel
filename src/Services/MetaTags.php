<?php

namespace Devel\Services;

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
     * @param boolean $override Override the tag value. The default behavior is to append new value to the previous values.
     * @return void
     */
    public static function setTag(string $name, string $value, bool $override = false): void
    {
        if (!isset(static::$tags[$name]) || $override) {
            static::$tags[$name] = new MetaTag($name, $value);
        } else {
            static::$tags[$name]->addValue($value);
        }
    }

    /**
     * Set one or more meta tags
     *
     * @param array $tags
     * @param boolean $override Override the tags values. The default behavior is to append new values to the previous values.
     * @return void
     */
    public static function setTags(array $tags, bool $override = false): void
    {
        foreach ($tags as $name => $value) {
            static::setTag($name, $value, $override);
        }
    }
}
