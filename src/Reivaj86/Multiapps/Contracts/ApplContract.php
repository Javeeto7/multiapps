<?php

namespace Reivaj\Multiapps\Contracts;

interface ApplContract
{
    /**
     * App belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

}
