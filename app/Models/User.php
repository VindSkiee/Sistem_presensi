<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'admin_id'];
    protected $hidden = ['password', 'remember_token'];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }

    public function schedules()
{
    return $this->belongsToMany(Schedule::class, 'schedule_person', 'user_id', 'schedule_id');
}


    public function users()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    // User -> dimiliki oleh satu admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function hasRole($role): bool
    {
        return $this->role === $role;
    }
}
