<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = ['date', 'day_name', 'is_validated', 'admin_id', 'photo'];

    public function persons()
    {
        return $this->belongsToMany(Person::class, 'schedule_person');
    }


    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
}
