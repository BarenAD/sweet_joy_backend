<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')->using(RolePermission::class);
    }
}
