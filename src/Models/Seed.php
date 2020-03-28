<?php

namespace Devel\Models;

class Seed extends Model
{
    public $table = 'devel_seeds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module',
        'model',
        'object_id',
    ];
}
