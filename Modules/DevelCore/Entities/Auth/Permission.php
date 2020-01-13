<?php

namespace Modules\DevelCore\Entities\Auth;

use Modules\DevelCore\Entities\Model;

class Permission extends Model
{
    public $table = 'user_permissions';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'name',
    ];

    protected $searchable = [
        'key',
        'name',
    ];
}
