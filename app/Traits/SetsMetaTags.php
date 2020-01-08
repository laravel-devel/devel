<?php

namespace App\Traits;

use App\Services\Seo\MetaTags;

trait SetsMetaTags
{
    /**
     * Set one or more meta tags
     *
     * @param string|array $tags
     * @param string $value
     * @return void
     */
    public function setMeta($tags, string $value = null): void
    {
        if (is_array($tags)) {
            MetaTags::setTags($tags);
        } else {
            MetaTags::setTag($tags, $value);
        }
    }
}