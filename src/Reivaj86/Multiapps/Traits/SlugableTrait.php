<?php

namespace Reivaj86\Multiapps\Traits;

trait SlugableTrait
{
    /**
     * Set slug attribute.
     *
     * @param string $value
     * @return void
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = str_slug($value, config('multiapps.separator'));
    }
}
