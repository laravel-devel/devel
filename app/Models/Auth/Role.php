<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $table = 'user_roles';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role',
    ];

    /**
     * A role has many permissions
     *
     * @return void
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_role_permission', 'role', 'permission', 'key');
    }
}
