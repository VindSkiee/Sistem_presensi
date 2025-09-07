<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'persons';

    protected $fillable = ['name', 'email', 'password', 'phone', 'user_id'];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'schedule_person', 'person_id', 'schedule_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
