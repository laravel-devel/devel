<?php

namespace Devel\Traits;

use Devel\Services\MetaTags;

trait SetsMetaTags
{
    /**
     * Set one or more meta tags
     *
     * @param string|array $tags
     * @param string|boolean $value When sending an array of tags, this param works as the $overriden param
     * @param boolean $override Override the tag value. The default behavior is to append new value to the previous values.
     * @return void
     */
    public function setMeta($tags, $value = null, bool $override = false): void
    {
        if (is_array($tags)) {
            MetaTags::setTags($tags, $value === true ? true : false);
        } else {
            MetaTags::setTag($tags, $value, $override);
        }
    }
}
