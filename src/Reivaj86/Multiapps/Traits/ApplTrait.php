<?php

namespace Reivaj86\Multiapps\Traits;

trait ApplTrait
{

    /**
     * Appl belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.model'))->withTimestamps();
    }

}
