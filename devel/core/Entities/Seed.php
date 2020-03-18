<?php

namespace Devel\Core\Entities;

class Seed extends Model
{
    public $table = '_seeds';

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
