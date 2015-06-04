<?php

namespace Reivaj86\Multiapps\Contracts;

interface ApplContract
{
    /**
     * App belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

}
