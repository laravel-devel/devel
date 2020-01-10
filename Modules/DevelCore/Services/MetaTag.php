<?php

namespace Modules\DevelCore\Services;

class MetaTag
{
    /**
     * Tag name (type)
     *
     * @var string
     */
    protected $name;

    /**
     * Tag value
     *
     * @var array
     */
    protected $values = [];

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->values = [$value];
    }

    /**
     * Add a value to the array of values
     *
     * @param string $value
     * @return void
     */
    public function addValue(string $value): void
    {
        array_unshift($this->values, $value);
    }

    /**
     * Generate HTML for the tag
     *
     * @return string
     */
    public function toHtml(): string
    {
        if (!isset(MetaTags::TEMPLATES[$this->name])) {
            return '';
        }

        $template = MetaTags::TEMPLATES[$this->name];

        return str_replace('{value}', implode(' - ', $this->values), $template);
    }
}
