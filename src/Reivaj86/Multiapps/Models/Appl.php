<?php

namespace Reivaj86\Multiapps\Models;

use Reivaj86\Multiapps\Contracts\ApplContract;
use Reivaj86\Multiapps\ApplTrait;
use Reivaj86\Multiapps\SlugableTrait;
use Illuminate\Database\Eloquent\Model;

class Appl extends Model implements ApplContract
{
    use ApplTrait, SlugableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'level'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = config('multiapps.connection')) { $this->connection = $connection; }
    }
}
