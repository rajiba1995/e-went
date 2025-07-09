<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $guarded = [];

    protected $fillable = [
        'designation',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function designationData(){
        return $this->belongsTo(Designation::class,'designation', 'id');
    }

    public function hasPermissionByRoute($route)
    {
        if (!$this->designationData) {
            return false; // Ensure user has a designation
        }

        return $this->designationData->permissions()->where('route', $route)->exists();
    }

}
