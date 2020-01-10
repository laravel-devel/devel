<?php

namespace App\Models\Auth;

use App\Models\Model;

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
}
