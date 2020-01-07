<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

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
